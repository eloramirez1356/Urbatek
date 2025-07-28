// Funcionalidad para el formulario de ticket simplificado

let workIndex = 1;

// Detectar cambios en la selección de máquina
document.getElementById('machine').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const machineType = selectedOption.getAttribute('data-type');
    
    // Ocultar todos los campos específicos
    document.querySelectorAll('.truck-fields, .machine-fields').forEach(field => {
        field.style.display = 'none';
        // Deshabilitar campos ocultos
        field.querySelectorAll('input, select').forEach(input => {
            input.required = false;
        });
    });
    
    // Mostrar campos según el tipo de máquina
    if (machineType === 'truck') {
        document.querySelectorAll('.truck-fields').forEach(field => {
            field.style.display = 'block';
            // Habilitar campos requeridos para camiones
            field.querySelectorAll('input[required]').forEach(input => {
                input.required = true;
            });
        });
    } else if (machineType === 'machine') {
        document.querySelectorAll('.machine-fields').forEach(field => {
            field.style.display = 'block';
            // Habilitar campos requeridos para máquinas
            field.querySelectorAll('input[required]').forEach(input => {
                input.required = true;
            });
        });
    }
});

// Agregar nueva obra
function addWork() {
    const container = document.getElementById('worksContainer');
    const newWork = document.createElement('div');
    newWork.className = 'work-entry';
    newWork.setAttribute('data-index', workIndex);
    
    // Obtener el HTML de la primera obra como plantilla
    const template = container.querySelector('.work-entry').cloneNode(true);
    
    // Actualizar índices y nombres
    updateWorkEntry(template, workIndex);
    
    // Limpiar valores
    template.querySelectorAll('input, select, textarea').forEach(input => {
        if (input.type !== 'hidden') {
            input.value = '';
        }
    });
    
    // Ocultar campos específicos inicialmente
    template.querySelectorAll('.truck-fields, .machine-fields').forEach(field => {
        field.style.display = 'none';
        // Deshabilitar campos ocultos
        field.querySelectorAll('input, select').forEach(input => {
            input.required = false;
        });
    });
    
    container.appendChild(template);
    workIndex++;
    
    // Actualizar números de obra
    updateWorkNumbers();
}

// Eliminar obra
function removeWork(button) {
    const workEntry = button.closest('.work-entry');
    const container = document.getElementById('worksContainer');
    
    // No permitir eliminar si solo hay una obra
    if (container.querySelectorAll('.work-entry').length <= 1) {
        alert('Debe haber al menos una obra.');
        return;
    }
    
    workEntry.remove();
    updateWorkNumbers();
}

// Actualizar entrada de obra
function updateWorkEntry(element, index) {
    // Actualizar título
    const title = element.querySelector('h4');
    title.textContent = `Obra #${index + 1}`;
    
    // Actualizar nombres de campos
    element.querySelectorAll('input, select, textarea').forEach(input => {
        const name = input.getAttribute('name');
        if (name) {
            input.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
        }
    });
}

// Actualizar números de obra
function updateWorkNumbers() {
    const works = document.querySelectorAll('.work-entry');
    works.forEach((work, index) => {
        const title = work.querySelector('h4');
        title.textContent = `Obra #${index + 1}`;
        work.setAttribute('data-index', index);
        
        // Actualizar nombres de campos
        work.querySelectorAll('input, select, textarea').forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace(/\[\d+\]/, `[${index}]`));
            }
        });
    });
}

// Validación del formulario
document.getElementById('simpleTicketForm').addEventListener('submit', function(e) {
    const employee = document.getElementById('employee').value;
    const machine = document.getElementById('machine').value;
    const date = document.getElementById('date').value;
    
    if (!employee || !machine || !date) {
        e.preventDefault();
        alert('Por favor complete todos los campos obligatorios.');
        return;
    }
    
    // Obtener tipo de máquina seleccionada
    const machineSelect = document.getElementById('machine');
    const selectedOption = machineSelect.options[machineSelect.selectedIndex];
    const machineType = selectedOption.getAttribute('data-type');
    
    // Validar que al menos una obra tenga datos
    let hasValidWork = false;
    let errors = [];
    
    document.querySelectorAll('.work-entry').forEach((work, index) => {
        const site = work.querySelector('select[name*="[site]"]').value;
        const hours = work.querySelector('input[name*="[hours]"]').value;
        
        if (site && hours) {
            hasValidWork = true;
            
            // Validar campos específicos según tipo de máquina
            if (machineType === 'truck') {
                const numTravels = work.querySelector('input[name*="[num_travels]"]').value;
                const tons = work.querySelector('input[name*="[tons]"]').value;
                const portages = work.querySelector('input[name*="[portages]"]').value;
                const provider = work.querySelector('input[name*="[provider]"]').value;
                const liters = work.querySelector('input[name*="[liters]"]').value;
                
                if (!numTravels || !tons || !portages || !provider || !liters) {
                    errors.push(`Obra #${index + 1}: Complete todos los campos obligatorios para camión`);
                }
            } else if (machineType === 'machine') {
                const hammerHours = work.querySelector('input[name*="[hammer_hours]"]').value;
                const spoonHours = work.querySelector('input[name*="[spoon_hours]"]').value;
                
                if (!hammerHours || !spoonHours) {
                    errors.push(`Obra #${index + 1}: Complete todos los campos obligatorios para máquina`);
                }
            }
        }
    });
    
    if (!hasValidWork) {
        e.preventDefault();
        alert('Debe completar al menos una obra con sitio y horas/viajes.');
        return;
    }
    
    if (errors.length > 0) {
        e.preventDefault();
        alert('Errores de validación:\n' + errors.join('\n'));
        return;
    }
});

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    // Establecer fecha actual si no hay fecha
    const dateInput = document.getElementById('date');
    if (!dateInput.value) {
        dateInput.value = new Date().toISOString().split('T')[0];
    }
}); 