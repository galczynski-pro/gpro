<?php 
$use_bootstrap_icons = true;
$header_min = true;
$bg_white = true;
require_once(__DIR__ . '/inc/includes.php');

define('META_TITLE', $seoConfig['home_meta_title']);
define('META_DESCRIPTION', $seoConfig['home_meta_description']);
require_once(__DIR__ . '/inc/header.php');
?>

<section class="section-spacing">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center">
        <img src="<?php echo $base_url; ?>/img/hero.jpg" alt="Hero" class="img-fluid mb-4" onerror="this.style.display='none'">
        <h1 class="mb-3"><?php echo isset($lang['main_title']) ? $lang['main_title'] : 'Witaj'; ?></h1>
        <p class="lead mb-4"><?php echo isset($lang['sub_title']) ? $lang['sub_title'] : 'Załóż konto lub zaloguj się, aby kontynuować.'; ?></p>

        <div class="d-flex justify-content-center gap-3 mt-3">
          <a href="<?php echo $base_url; ?>/sign-up" class="btn btn-primary btn-lg">
            <i class="bi bi-box-arrow-in-right"></i> <?php echo $lang['sign_up']; ?>
          </a>
          <a href="<?php echo $base_url; ?>/sign-in" class="btn btn-outline-secondary btn-lg">
            <i class="bi bi-person-circle"></i> <?php echo $lang['sign_in']; ?>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once(__DIR__ . '/inc/footer.php'); ?>

