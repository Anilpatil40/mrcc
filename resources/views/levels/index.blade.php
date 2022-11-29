<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <script src="https://code.jquery.com/jquery-3.6.1.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  <title>Levels</title>
</head>
<body>
  <div class="container">
    <h1>Levels</h1>
    <div style="cursor: pointer" class="open-add-modal-btn">
      <i class="bi bi-plus-circle"></i>
    </div>
    <div id="levels-container"></div>
  </div>

  {{-- add level modal  --}}
  <div class="modal fade" id="level-add-modal">
    <div class="modal-dialog modal-dialog-centered">
       <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5">Add Level</h1>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="toggleAddLevelModal()">Close</button>
          <button type="button" class="btn btn-primary" id="submit-add-level-btn">Add</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(() => {
      $.ajax({
        url: `/api/levels`,
        success: (data) => {
          renderLevelContainer(data.data.levels);
        }
      })
    });

    function renderLevelContainer(levels = []){
      levels.sort((levelA, levelB) => {
        if(levelA.connectedWith == levelB.connectedWith) return 0;
        if(levelB.connectedWith == null && levelA.connectedWith < levelB.id ) return -1;
      });
      const html = levels.reduce((html, {id, label, connectedWith}) => {
        return html + (
          `<div class='mt-1'>`+
            `<div class='d-inline' style='margin-left: ${connectedWith ? 30 : 0}px'>${label}</div>` +
            (!connectedWith ? (
              `<i 
              style="cursor: pointer" 
              class="bi bi-plus-circle open-add-modal-btn" 
              data-connected-with='${id}'
              ></i>
              `
            ) : ``) +
            `<i 
              style="cursor: pointer" 
              class="bi bi-trash-fill delete-level-btn" 
              data-level-id='${id}'
              ></i>` +
          `</div>`
        );
      }, '')
      $('#levels-container').html(html);

      addListerenersToLevelContainer();
    }

    function addListerenersToLevelContainer(){
      $('.open-add-modal-btn').on('click', (e) => {
        const connectedWith = $(e.target).data('connected-with');
        $('#level-add-modal .modal-body').html(
          `<input id='input-add-level' class='w-100' data-connected-with='${connectedWith ? connectedWith : ""}' />`
        );
        toggleAddLevelModal();
      });

      $('.delete-level-btn').on('click', (e) => {
        const id = $(e.target).data('level-id');

        $.ajax({
          type: 'POST',
          url: `/api/levels/${id}/delete`,
          success: (data) => {
            renderLevelContainer(data.data.levels);
          },
          processData: false
        })
      });
    }

    function toggleAddLevelModal(){
      $('#level-add-modal').modal('toggle');
    }


    $('#submit-add-level-btn').on('click', () => {
      const connectedWith = $('#input-add-level').data('connected-with');
      const label = $('#input-add-level').val();

      $.ajax({
        type: 'POST',
        url: `/api/levels/store`,
        data: new URLSearchParams({
          label,
          connectedWith
        }),
        success: (data) => {
          renderLevelContainer(data.data.levels);
          toggleAddLevelModal();
        },
        processData: false
      })
    });


  </script>
</body>
</html>