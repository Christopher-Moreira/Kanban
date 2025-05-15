<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sicoob Kanban</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .drop-zone {
      transition: background-color 0.3s;
      min-height: 400px;
    }

    .sicoob-bg {
      background: linear-gradient(135deg, #e0f2f1 0%, #c8e6c9 100%);
    }

    .status-green {
      background: linear-gradient(135deg, #00695c 0%, #26a69a 100%);
    }

    .status-orange {
      background: linear-gradient(135deg, #ef6c00 0%, #ffb74d 100%);
    }

    .status-red {
      background: linear-gradient(135deg, #b71c1c 0%, #ef5350 100%);
    }

    .column-glass {
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(203, 213, 225, 0.3);
    }
    
    .priority-high {
      background-color: #f87171;
      color: white;
    }
    
    .priority-medium {
      background-color: #fbbf24;
      color: white;
    }
    
    .priority-low {
      background-color: #60a5fa;
      color: white;
    }
    
    .responsible-badge {
      background-color: #4f46e5;
      color: white;
    }
    
    .ap-badge {
      background-color: #f97316;
      color: white;
    }
  </style>
</head>
<body class="sicoob-bg min-h-screen">
  <div class="container mx-auto p-6 max-w-7xl">
    <!-- Cabeçalho -->
    <header class="mb-10 flex flex-col md:flex-row items-center justify-center gap-6 md:justify-start">
      <img src="images/sicoob.png" alt="Logo Sicoob" class="h-20 md:h-24 transition-opacity hover:opacity-90" />
      <div class="text-center md:text-left">
        <h1 class="text-3xl md:text-4xl font-bold text-[#004A8D] mb-2">
          Gestão de Atividades
        </h1>
        <p class="text-gray-600">Quadro Kanban Institucional</p>
      </div>
    </header>

    <!-- Botão -->
    <div class="mb-8 flex justify-center">
      <button id="addCard" class="bg-[#005f56] hover:bg-[#004a44] text-white px-8 py-4 rounded-xl shadow-lg transition-all flex items-center gap-3">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Nova Tarefa
      </button>
    </div>

    <!-- Modal para nova tarefa -->
    <div id="taskModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
      <div class="bg-white rounded-xl p-6 w-full max-w-md">
        <h3 class="text-xl font-bold mb-4">Adicionar Nova Tarefa</h3>
        <form id="taskForm">
          <div class="mb-4">
            <label class="block text-gray-700 mb-2">Nome:</label>
            <input type="text" id="taskName" class="w-full px-3 py-2 border rounded-lg" required>
          </div>
          <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block text-gray-700 mb-2">Valor Contrato:</label>
              <input type="text" id="contractValue" class="w-full px-3 py-2 border rounded-lg" placeholder="R$ 0,00">
            </div>
            <div>
              <label class="block text-gray-700 mb-2">Valor Parcela:</label>
              <input type="text" id="installmentValue" class="w-full px-3 py-2 border rounded-lg" placeholder="R$ 0,00">
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
              <label class="block text-gray-700 mb-2">Prioridade:</label>
              <select id="taskPriority" class="w-full px-3 py-2 border rounded-lg">
                <option value="high">90</option>
                <option value="medium" selected>31</option>
                <option value="low">60</option>
              </select>
            </div>
            <div>
              <label class="block text-gray-700 mb-2">Responsável (R):</label>
              <input type="text" id="taskResponsible" class="w-full px-3 py-2 border rounded-lg" placeholder="Iniciais">
            </div>
          </div>
          <div class="mb-4">
            <label class="block text-gray-700 mb-2">Data de Início:</label>
            <input type="date" id="taskDate" class="w-full px-3 py-2 border rounded-lg" required>
          </div>
          <div class="flex justify-end gap-3">
            <button type="button" id="cancelTask" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Cancelar</button>
            <button type="submit" class="px-4 py-2 bg-[#005f56] text-white rounded-lg hover:bg-[#004a44]">Adicionar</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal para visualizar tarefa -->
    <div id="viewTaskModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
      <div class="bg-white rounded-xl p-6 w-full max-w-md">
        <h3 class="text-xl font-bold mb-4">Detalhes da Cooperado</h3>
        <div id="taskDetails" class="space-y-4">
          <div>
            <label class="block text-gray-700 font-medium mb-1">Nome:</label>
            <p id="viewTaskName" class="text-gray-800"></p>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-gray-700 font-medium mb-1">Valor Contrato:</label>
              <p id="viewContractValue" class="text-gray-800"></p>
            </div>
            <div>
              <label class="block text-gray-700 font-medium mb-1">Valor Parcela:</label>
              <p id="viewInstallmentValue" class="text-gray-800"></p>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-gray-700 font-medium mb-1">Prioridade:</label>
              <p id="viewPriority" class="text-gray-800"></p>
            </div>
            <div>
              <label class="block text-gray-700 font-medium mb-1">Responsável:</label>
              <p id="viewResponsible" class="text-gray-800"></p>
            </div>
          </div>
          <div>
            <label class="block text-gray-700 font-medium mb-1">Data de Início:</label>
            <p id="viewTaskDate" class="text-gray-800"></p>
          </div>
          <div>
            <label class="block text-gray-700 font-medium mb-1">Status:</label>
            <p id="viewTaskStatus" class="text-gray-800"></p>
          </div>
          <div>
            <label class="block text-gray-700 font-medium mb-1">ID:</label>
            <p id="viewTaskId" class="text-gray-800"></p>
          </div>
        </div>
        <div class="flex justify-end mt-6">
          <button type="button" id="closeViewTask" class="px-4 py-2 bg-[#005f56] text-white rounded-lg hover:bg-[#004a44]">Fechar</button>
        </div>
      </div>
    </div>


    <!-- Colunas -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <div class="column-glass p-6 rounded-2xl shadow-xl border-l-[6px] border-red-600">
        <h2 class="text-xl font-bold mb-6 p-4 rounded-xl text-white status-red shadow-md">AGUARDANDO</h2>
        <div class="drop-zone space-y-4" id="aguardando"></div>
      </div>

      <div class="column-glass p-6 rounded-2xl shadow-xl border-l-[6px] border-orange-600">
        <h2 class="text-xl font-bold mb-6 p-4 rounded-xl text-white status-orange shadow-md">EM ANDAMENTO</h2>
        <div class="drop-zone space-y-4" id="atendendo"></div>
      </div>

      <div class="column-glass p-6 rounded-2xl shadow-xl border-l-[6px] border-green-800">
        <h2 class="text-xl font-bold mb-6 p-4 rounded-xl text-white status-green shadow-md">CONCLUÍDO</h2>
        <div class="drop-zone space-y-4" id="realizado"></div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      let cardCounter = 0;


      document.getElementById('addCard').addEventListener('click', () => {
        const card = document.createElement('div');
        cardCounter++;

      const taskModal = document.getElementById('taskModal');
      const viewTaskModal = document.getElementById('viewTaskModal');
      const taskForm = document.getElementById('taskForm');
      const cancelTask = document.getElementById('cancelTask');
      const closeViewTask = document.getElementById('closeViewTask');

      // Mostrar modal ao clicar no botão Nova Tarefa
      document.getElementById('addCard').addEventListener('click', () => {
        taskModal.classList.remove('hidden');
        // Definir data atual como padrão
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('taskDate').value = today;
      });

      // Fechar modal ao clicar em Cancelar
      cancelTask.addEventListener('click', () => {
        taskModal.classList.add('hidden');
        taskForm.reset();
      });

      // Fechar modal de visualização
      closeViewTask.addEventListener('click', () => {
        viewTaskModal.classList.add('hidden');
      });

      // Fechar modais ao clicar fora deles
      taskModal.addEventListener('click', (e) => {
        if (e.target === taskModal) {
          taskModal.classList.add('hidden');
          taskForm.reset();
        }
      });

      viewTaskModal.addEventListener('click', (e) => {
        if (e.target === viewTaskModal) {
          viewTaskModal.classList.add('hidden');
        }
      });

      // Processar formulário de nova tarefa
      taskForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const name = document.getElementById('taskName').value;
        const contractValue = document.getElementById('contractValue').value;
        const installmentValue = document.getElementById('installmentValue').value;
        const priority = document.getElementById('taskPriority').value;
        const responsible = document.getElementById('taskResponsible').value;
        const taskDate = document.getElementById('taskDate').value;
        
        addNewCard(name, contractValue, installmentValue, priority, responsible, taskDate);
        
        taskModal.classList.add('hidden');
        taskForm.reset();
      });

      function addNewCard(name, contractValue, installmentValue, priority, responsible, taskDate) {
        const card = document.createElement('div');
        cardCounter++;
        const cardId = cardCounter.toString().padStart(3, '0');
        
        // Determinar classe de prioridade
        let priorityClass = '';
        let priorityText = '';
        switch(priority) {
          case 'high':
            priorityClass = 'priority-high';
            priorityText = '90';
            break;
          case 'medium':
            priorityClass = 'priority-medium';
            priorityText = '31';
            break;
          case 'low':
            priorityClass = 'priority-low';
            priorityText = '60';
            break;
        }
        
        // Verificar condição para mostrar tag A.P
        const showAPBadge = checkAPCondition(taskDate);
        

        card.className = 'card bg-white p-5 rounded-xl shadow-md cursor-move relative hover:shadow-lg transition-all mb-4 border-l-4 border-red-600 group';
        card.draggable = true;
        card.innerHTML = `
          <div class="flex justify-between items-center mb-3">

            <span class="font-semibold text-gray-800">Tarefa ${cardCounter}</span>
            <span class="text-sm px-2 py-1 rounded-full bg-red-200 text-red-900">Novo</span>
          </div>
          <p class="text-gray-600 text-sm mb-4">Descrição da tarefa...</p>
          <div class="flex justify-between items-center text-xs text-gray-400">
            <span>${new Date().toLocaleDateString()}</span>
            <span>#${cardCounter.toString().padStart(3, '0')}</span>

            <span class="font-semibold text-gray-800">${name}</span>
            <div class="flex gap-2">
              ${responsible ? `<span class="text-xs px-2 py-1 rounded-full responsible-badge">R: ${responsible}</span>` : ''}
              <span class="text-sm px-2 py-1 rounded-full ${priorityClass}">${priorityText}</span>
              ${showAPBadge ? '<span class="text-xs px-2 py-1 rounded-full ap-badge">A.P</span>' : ''}
            </div>
          </div>
          <div class="mb-2">
            <p class="text-sm text-gray-600"><span class="font-medium">Valor Contrato:</span> ${contractValue || 'Não informado'}</p>
            <p class="text-sm text-gray-600"><span class="font-medium">Valor Parcela:</span> ${installmentValue || 'Não informado'}</p>
          </div>
          <div class="flex justify-between items-center text-xs text-gray-400">
            <span>${new Date(taskDate).toLocaleDateString()}</span>
            <span>#${cardId}</span>
          </div>
          <button class="delete-btn absolute -top-3 -right-3 bg-white rounded-full p-1 shadow-md hover:bg-red-100 transition-colors opacity-0 group-hover:opacity-100">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        `;
        card.dataset.status = 'aguardando';

        document.getElementById('aguardando').appendChild(card);
        initCardEvents(card);
      });

        card.dataset.id = cardId;
        card.dataset.name = name;
        card.dataset.contractValue = contractValue;
        card.dataset.installmentValue = installmentValue;
        card.dataset.priority = priorityText;
        card.dataset.responsible = responsible;
        card.dataset.taskDate = taskDate;
        
        document.getElementById('aguardando').appendChild(card);
        initCardEvents(card);
      }
        
      function checkAPCondition(taskDate) {
        const today = new Date();
        const inputDate = new Date(taskDate);
        
        // Obter o último dia do mês atual
        const lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        
        // Calcular dias entre hoje e o final do mês
        const daysToEndOfMonth = Math.floor((lastDayOfMonth - today) / (1000 * 60 * 60 * 24));
        
        // Calcular dias desde a data de entrada
        const daysSinceInput = Math.floor((today - inputDate) / (1000 * 60 * 60 * 24));
        
        // Verificar condição: dias desde entrada + dias até final do mês >= 90
        return (daysSinceInput + daysToEndOfMonth) >= 90;
      }

      function showTaskDetails(card) {
        document.getElementById('viewTaskName').textContent = card.dataset.name || 'Não informado';
        document.getElementById('viewContractValue').textContent = card.dataset.contractValue || 'Não informado';
        document.getElementById('viewInstallmentValue').textContent = card.dataset.installmentValue || 'Não informado';
        document.getElementById('viewPriority').textContent = card.dataset.priority || 'Não informado';
        document.getElementById('viewResponsible').textContent = card.dataset.responsible || 'Não informado';
        document.getElementById('viewTaskDate').textContent = card.dataset.taskDate ? new Date(card.dataset.taskDate).toLocaleDateString() : 'Não informado';
        document.getElementById('viewTaskStatus').textContent = getStatusText(card.dataset.status);
        document.getElementById('viewTaskId').textContent = `#${card.dataset.id}`;
        
        viewTaskModal.classList.remove('hidden');
      }

      function getStatusText(status) {
        switch(status) {
          case 'aguardando': return 'Aguardando';
          case 'atendendo': return 'Em Andamento';
          case 'realizado': return 'Concluído';
          default: return 'Não informado';
        }
      }


      document.querySelectorAll('.drop-zone').forEach(zone => {
        zone.addEventListener('click', (e) => {
          if (e.target.closest('.delete-btn')) {
            e.target.closest('.card').remove();
          }
        });
      });

      let draggedCard = null;

      document.querySelectorAll('.drop-zone').forEach(zone => {
        zone.addEventListener('dragover', (e) => {
          e.preventDefault();
          zone.style.backgroundColor = 'rgba(241, 245, 249, 0.5)';
        });

        zone.addEventListener('dragleave', () => {
          zone.style.backgroundColor = '';
        });

        zone.addEventListener('drop', (e) => {
          e.preventDefault();
          zone.style.backgroundColor = '';
          if (draggedCard) {
            zone.appendChild(draggedCard);
            const statusColors = {
              'aguardando': 'border-red-600',
              'atendendo': 'border-orange-600',
              'realizado': 'border-green-800'
            };
            draggedCard.className = draggedCard.className.replace(/border-(green|orange|red)-\d+/g, '');
            draggedCard.classList.add(statusColors[zone.id]);
            draggedCard.dataset.status = zone.id;
          }
        });
      });

      function initCardEvents(card) {
        card.addEventListener('dragstart', () => {
          draggedCard = card;
          setTimeout(() => card.style.opacity = '0.5', 0);
        });

        card.addEventListener('dragend', () => {
          draggedCard = null;
          card.style.opacity = '1';
        });

      }

  

      initialCards.forEach((cardData, index) => {
        const cardElement = document.createElement('div');
      


        // Adicionar evento de duplo clique
        let clickCount = 0;
        card.addEventListener('click', (e) => {
          // Evitar que o duplo clique seja acionado quando clicar no botão de deletar
          if (e.target.closest('.delete-btn')) return;
          
          clickCount++;
          if (clickCount === 1) {
            setTimeout(() => {
              if (clickCount === 1) {
                // Clique único
              } else {
                // Duplo clique
                showTaskDetails(card);
              }
              clickCount = 0;
            }, 300);
          }
        });
      }

      const initialCards = [];
      initialCards.forEach((cardData, index) => {
        const cardElement = document.createElement('div');

        cardElement.draggable = true;
        cardElement.dataset.status = cardData.status;
        document.getElementById(cardData.status).appendChild(cardElement);
        initCardEvents(cardElement);
      });

    });
  </script>
</body>
</html>

    }); 
  </script>
</body>
</html> 
