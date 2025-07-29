// Funcionalidad para el formulario de ticket simplificado

let workIndex = 1;

// Detectar cambios en la selección de empleado
document.getElementById('employee').addEventListener('change', function() {
    const employeeId = this.value;
    if (employeeId) {
        loadEmployeeSites(employeeId);
    } else {
        // Limpiar obras si no hay empleado seleccionado
        document.querySelectorAll('select[name*="[site]"]').forEach(select => {
            select.innerHTML = '<option value="">Seleccionar obra</option>';
        });
    }
});

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
            // Habilitar campos requeridos básicos para camiones
            field.querySelectorAll('input[required], select[required]').forEach(input => {
                input.required = true;
            });
        });
        
        // Mostrar botón de agregar obra para camiones
        document.querySelector('.btn-add-work').style.display = 'flex';
    } else if (machineType === 'machine') {
        document.querySelectorAll('.machine-fields').forEach(field => {
            field.style.display = 'block';
            // Habilitar campos requeridos para máquinas
            field.querySelectorAll('input[required]').forEach(input => {
                input.required = true;
            });
        });
        
        // Ocultar botón de agregar obra para máquinas
        document.querySelector('.btn-add-work').style.display = 'none';
        
        // Eliminar obras adicionales para máquinas (solo mantener la primera)
        const works = document.querySelectorAll('.work-entry');
        for (let i = 1; i < works.length; i++) {
            works[i].remove();
        }
        workIndex = 1;
        updateWorkNumbers();
    }
});

// Función para alternar campos según tipo de operación
function toggleOperationFields() {
    const operationType = document.getElementById('operationType').value;
    
    // Ocultar todos los campos específicos
    document.querySelectorAll('.supply-fields, .removal-fields').forEach(field => {
        field.style.display = 'none';
        // Deshabilitar campos ocultos
        field.querySelectorAll('input, select').forEach(input => {
            input.required = false;
        });
    });
    
    // Mostrar campos según tipo de operación
    if (operationType === 'supply') {
        document.querySelectorAll('.supply-fields').forEach(field => {
            field.style.display = 'block';
            // Habilitar campos requeridos para suministro
            field.querySelectorAll('input[required]').forEach(input => {
                input.required = true;
            });
        });
    } else if (operationType === 'removal') {
        document.querySelectorAll('.removal-fields').forEach(field => {
            field.style.display = 'block';
            // Habilitar campos requeridos para retirada
            field.querySelectorAll('input[required]').forEach(input => {
                input.required = true;
            });
        });
    }
}

// Cargar obras del empleado
function loadEmployeeSites(employeeId) {
    fetch(`/es/admin/employee/${employeeId}/sites`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(sites => {
            // Actualizar todos los selects de obras
            document.querySelectorAll('select[name*="[site]"]').forEach(select => {
                select.innerHTML = '<option value="">Seleccionar obra</option>';
                sites.forEach(site => {
                    const option = document.createElement('option');
                    option.value = site.id;
                    option.textContent = site.name;
                    select.appendChild(option);
                });
            });
        })
        .catch(error => {
            console.error('Error loading employee sites:', error);
            alert('Error al cargar las obras del empleado');
        });
}

// Agregar nueva obra (solo para camiones)
function addWork() {
    const machineSelect = document.getElementById('machine');
    const selectedOption = machineSelect.options[machineSelect.selectedIndex];
    const machineType = selectedOption.getAttribute('data-type');
    
    // Solo permitir agregar obras para camiones
    if (machineType !== 'truck') {
        alert('Solo se pueden agregar múltiples obras para camiones.');
        return;
    }
    
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
    template.querySelectorAll('.truck-fields, .machine-fields, .supply-fields, .removal-fields').forEach(field => {
        field.style.display = 'none';
        // Deshabilitar campos ocultos
        field.querySelectorAll('input, select').forEach(input => {
            input.required = false;
        });
    });
    
    // Mostrar campos de camión si es camión
    if (machineType === 'truck') {
        template.querySelectorAll('.truck-fields').forEach(field => {
            field.style.display = 'block';
            field.querySelectorAll('input[required], select[required]').forEach(input => {
                input.required = true;
            });
        });
    }
    
    container.appendChild(template);
    workIndex++;
    
    // Actualizar números de obra
    updateWorkNumbers();
    
    // Cargar obras del empleado en el nuevo select
    const employeeId = document.getElementById('employee').value;
    if (employeeId) {
        loadEmployeeSites(employeeId);
    }
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
    
    // Actualizar ID del select de operación
    const operationSelect = element.querySelector('select[name*="[operation_type]"]');
    if (operationSelect) {
        operationSelect.id = `operationType_${index}`;
        operationSelect.onchange = function() {
            toggleOperationFieldsForWork(this);
        };
    }
}

// Función para alternar campos en obras específicas
function toggleOperationFieldsForWork(select) {
    const workEntry = select.closest('.work-entry');
    const operationType = select.value;
    
    // Ocultar todos los campos específicos en esta obra
    workEntry.querySelectorAll('.supply-fields, .removal-fields').forEach(field => {
        field.style.display = 'none';
        field.querySelectorAll('input, select').forEach(input => {
            input.required = false;
        });
    });
    
    // Mostrar campos según tipo de operación
    if (operationType === 'supply') {
        workEntry.querySelectorAll('.supply-fields').forEach(field => {
            field.style.display = 'block';
            field.querySelectorAll('input[required]').forEach(input => {
                input.required = true;
            });
        });
    } else if (operationType === 'removal') {
        workEntry.querySelectorAll('.removal-fields').forEach(field => {
            field.style.display = 'block';
            field.querySelectorAll('input[required]').forEach(input => {
                input.required = true;
            });
        });
    }
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
        
        // Actualizar ID del select de operación
        const operationSelect = work.querySelector('select[name*="[operation_type]"]');
        if (operationSelect) {
            operationSelect.id = `operationType_${index}`;
            operationSelect.onchange = function() {
                toggleOperationFieldsForWork(this);
            };
        }
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
        const material = work.querySelector('select[name*="[material]"]').value;
        
        if (site && material) {
            hasValidWork = true;
            
            // Validar campos específicos según tipo de máquina
            if (machineType === 'truck') {
                const numTravels = work.querySelector('input[name*="[num_travels]"]').value;
                const operationType = work.querySelector('select[name*="[operation_type]"]').value;
                
                if (!numTravels || !operationType) {
                    errors.push(`Obra #${index + 1}: Complete todos los campos obligatorios para camión`);
                } else {
                    // Validar campos según tipo de operación
                    if (operationType === 'supply') {
                        const tons = work.querySelector('input[name*="[tons]"]').value;
                        const provider = work.querySelector('select[name*="[provider]"]').value;
                        
                        if (!tons || !provider) {
                            errors.push(`Obra #${index + 1}: Complete todos los campos obligatorios para suministro`);
                        }
                    } else if (operationType === 'removal') {
                        const provider = work.querySelector('select[name*="[provider]"]').value;
                        
                        if (!provider) {
                            errors.push(`Obra #${index + 1}: Complete todos los campos obligatorios para retirada`);
                        }
                    }
                }
            } else if (machineType === 'machine') {
                const hours = work.querySelector('input[name*="[hours]"]').value;
                const hammerHours = work.querySelector('input[name*="[hammer_hours]"]').value;
                const spoonHours = work.querySelector('input[name*="[spoon_hours]"]').value;
                
                if (!hours || !hammerHours || !spoonHours) {
                    errors.push(`Obra #${index + 1}: Complete todos los campos obligatorios para máquina`);
                }
            }
        }
    });
    
    if (!hasValidWork) {
        e.preventDefault();
        alert('Debe completar al menos una obra con sitio y material.');
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
    
    // Ocultar botón de agregar obra inicialmente
    document.querySelector('.btn-add-work').style.display = 'none';
}); 