<?php echo !defined("guvenlik") ? die("") : null; ?>
<div class="container">
<a class="navbar-brand" href="<?= $site ?>"><i class="fa fa-home"></i> <?= $arow->baslik ?></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item active">
        <a class="nav-link" href="<?= $site ?>">Ana Sayfa <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="videooner.php"><i class="fa fa-video"></i> Video Öner</a>
      </li>
    </ul>
  </div>
</div>