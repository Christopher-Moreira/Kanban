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
      
        cardElement.draggable = true;
        cardElement.dataset.status = cardData.status;
        document.getElementById(cardData.status).appendChild(cardElement);
        initCardEvents(cardElement);
      });
    });
  </script>
</body>
</html>
