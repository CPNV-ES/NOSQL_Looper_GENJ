<?php
$title = 'ExerciseLooper';

ob_start();
?>

<header class="heading results">
  <section class="container">
    <a href="/"><img src="/assets/img/logo.png"></a>
  </section>
</header>

<main class="container">

  <div class="row">
    <section class="column">
      <h1>Building</h1>
      <table class="records">
        <thead>
          <tr>
            <th>Title</th>
            <th></th>
          </tr>
        </thead>

        <tbody>
            <tr>
              <td>hello</td>
              <td>
                  <a title="Be ready for answers" rel="nofollow" data-method="put" href="/exercises/68?exercise%5Bstatus%5D=answering"><i class="fa fa-comment"></i></a>
                <a title="Manage fields" href="/exercises/68/fields"><i class="fa fa-edit"></i></a>
                <a data-confirm="Are you sure?" title="Destroy" rel="nofollow" data-method="delete" href="/exercises/68"><i class="fa fa-trash"></i></a>
              </td>
            </tr>
        </tbody>
      </table>
    </section>

    <section class="column">
      <h1>Answering</h1>
      <table class="records">
        <thead>
          <tr>
            <th>Title</th>
            <th></th>
          </tr>
        </thead>

        <tbody>
            <tr>
              <td>&lt;h1&gt;test&lt;/h1&gt;</td>
              <td>
                <a title="Show results" href="/exercises/74/results"><i class="fa fa-chart-bar"></i></a>
                <a title="Close" rel="nofollow" data-method="put" href="/exercises/74?exercise%5Bstatus%5D=closed"><i class="fa fa-minus-circle"></i></a>
              </td>
            </tr>
        </tbody>
      </table>
    </section>

    <section class="column">
      <h1>Closed</h1>
      <table class="records">
        <thead>
          <tr>
            <th>Title</th>
            <th></th>
          </tr>
        </thead>

        <tbody>
            <tr>
              <td>bbbbbbbbbbbbb</td>
              <td>
                <a title="Show results" href="/exercises/78/results"><i class="fa fa-chart-bar"></i></a>
                <a data-confirm="Are you sure?" title="Destroy" rel="nofollow" data-method="delete" href="/exercises/78"><i class="fa fa-trash"></i></a>
              </td>
            </tr>
        </tbody>
      </table>
    </section>
  </div>
</main>

<?php
$content = ob_get_clean();
require VIEW_DIR . '/template.php';
?>