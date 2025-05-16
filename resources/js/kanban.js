let draggedCard = null;

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.kanban-card').forEach(card => {
        card.addEventListener('dragstart', handleDragStart);
        card.addEventListener('dragend', () => {
            card.classList.remove('dragging');
        });
    });
});

function handleDragStart(event) {
    draggedCard = event.target;
    event.dataTransfer.setData('text/plain', event.target.id);
    event.dataTransfer.effectAllowed = 'move';
    event.target.classList.add('dragging');
}

function handleDragOver(event) {
    event.preventDefault();
    event.currentTarget.classList.add('dragover');
    event.dataTransfer.dropEffect = 'move';
}

function handleDragLeave(event) {
    event.currentTarget.classList.remove('dragover');
}

async function handleDrop(event) {
    event.preventDefault();
    event.currentTarget.classList.remove('dragover');
    
    const targetColumn = event.currentTarget.querySelector('.kanban-cards');
    const cardId = event.dataTransfer.getData('text/plain');
    const card = document.getElementById(cardId);
    
    const newStatus = targetColumn.id;
    const itemId = card.dataset.id;

    try {
        const response = await fetch(`/kanban/${itemId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                status: newStatus
            })
        });

        if (!response.ok) throw new Error('Falha na atualização');
        
        targetColumn.appendChild(card);
        console.log(`Card ${itemId} movido para ${newStatus}`);

    } catch (error) {
        console.error('Erro:', error);
        alert('Erro ao atualizar o status do card');
    }
}