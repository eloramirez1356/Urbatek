// Funcionalidad para la asignación de máquinas a empleados

let currentEmployeeId = null;
let allMachines = [];
let assignedMachines = [];

function openAssignMachines(employeeId, employeeName) {
    currentEmployeeId = employeeId;
    document.getElementById('employeeName').textContent = employeeName;
    document.getElementById('assignMachinesModal').style.display = 'block';
    
    // Cargar máquinas
    loadMachines(employeeId);
}

function closeAssignMachines() {
    document.getElementById('assignMachinesModal').style.display = 'none';
}

function loadMachines(employeeId) {
    fetch(`/es/admin/employees/${employeeId}/machines`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            allMachines = data.available || [];
            assignedMachines = data.assigned || [];
            renderMachines();
        })
        .catch(error => {
            console.error('Error loading machines:', error);
            console.error('Employee ID:', employeeId);
            alert(`Error al cargar las máquinas: ${error.message}`);
        });
}

function renderMachines() {
    const filter = document.getElementById('machineFilter').value;
    const availableContainer = document.getElementById('availableMachines');
    const assignedContainer = document.getElementById('assignedMachines');
    
    // Filtrar máquinas disponibles
    let availableToShow = allMachines;
    if (filter) {
        availableToShow = allMachines.filter(machine => {
            if (filter === 'machine') return machine.type === 'machine';
            if (filter === 'truck') return machine.type === 'truck';
            return true;
        });
    }
    
    // Renderizar máquinas disponibles
    availableContainer.innerHTML = availableToShow.map(machine => `
        <div class="machine-item">
            <div>
                <strong>${machine.name}</strong> (${machine.brand})
                <span class="badge ${machine.type === 'truck' ? 'badge-info' : 'badge-success'}">
                    ${machine.type === 'truck' ? 'Camión' : 'Máquina'}
                </span>
            </div>
            <button class="btn-assign" onclick="assignMachine(${machine.id})">
                Asignar
            </button>
        </div>
    `).join('');
    
    // Renderizar máquinas asignadas
    assignedContainer.innerHTML = assignedMachines.map(machine => `
        <div class="machine-item">
            <div>
                <strong>${machine.name}</strong> (${machine.brand})
                <span class="badge ${machine.type === 'truck' ? 'badge-info' : 'badge-success'}">
                    ${machine.type === 'truck' ? 'Camión' : 'Máquina'}
                </span>
            </div>
            <button class="btn-unassign" onclick="unassignMachine(${machine.id})">
                Desasignar
            </button>
        </div>
    `).join('');
}

function filterMachines() {
    renderMachines();
}

function assignMachine(machineId) {
    if (!currentEmployeeId) return;
    
    fetch(`/es/admin/employees/${currentEmployeeId}/machines/${machineId}/assign`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadMachines(currentEmployeeId);
            // Recargar la página para actualizar la tabla
            setTimeout(() => location.reload(), 500);
        } else {
            alert('Error al asignar la máquina');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al asignar la máquina');
    });
}

function unassignMachine(machineId) {
    if (!currentEmployeeId) return;
    
    fetch(`/es/admin/employees/${currentEmployeeId}/machines/${machineId}/unassign`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadMachines(currentEmployeeId);
            // Recargar la página para actualizar la tabla
            setTimeout(() => location.reload(), 500);
        } else {
            alert('Error al desasignar la máquina');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al desasignar la máquina');
    });
}

// Cerrar modal al hacer clic fuera de él
window.onclick = function(event) {
    const modal = document.getElementById('assignMachinesModal');
    if (event.target === modal) {
        closeAssignMachines();
    }
} 