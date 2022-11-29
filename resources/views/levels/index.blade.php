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
  <title>Levels</title>
</head>
<body>
  <div class="container">
    <h1>Levels</h1>
    <div id="levels-container"></div>
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
      const html = levels.reduce((html, {label, connectedWith}) => {
        return html + (
          `<div class='mt-1'>`+
            `<div style='margin-left: ${connectedWith ? 30 : 0}px'>${label}</div>` +
          `</div>`
        );
      }, '')
      $('#levels-container').html(html);
    }
  </script>
</body>
</html>