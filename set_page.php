<?php
/*
Sayfada yapılacak değişiklikleri ayarladan alır, uygular.
*/
if(@$ini['bg_set']!=''){ $bg_set=$ini['bg_set']; }else{ $bg_set="2d4299"; }
?>
<style>
.bg-set {
  background-color: #<?php echo $bg_set; ?>;
  background-image: linear-gradient(180deg, #<?php echo $bg_set; ?> 5%, #<?php echo $bg_set; ?> 100%);
  background-size: cover;
}
.bg-login-image {
  background: url("<?php echo $ini['bg_login']; ?>");  
  background-position: center;
  background-size: cover;
}
</style>