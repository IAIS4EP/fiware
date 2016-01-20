<? ini_set('default_charset', 'UTF-8');header('Content-Type: text/html; charset=UTF-8');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
    <?=$this->head->render_title()?>
    <?=$this->head->render_meta()?>
    <meta name="HandheldFriendly" content="True">

    <link href="<?=BASEURL?><?=$minicss?>" rel="stylesheet" media="all" type="text/css" />
    <?php //echo $this->head->render_items("css"); ?>
    <noscript><link rel="stylesheet" type="text/css" href="<?=CSS?>noJS.css"/></noscript>
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="<?=JS?>html5shiv.js"></script>
    <![endif]-->
     <!-- ****************** /STEP 1: INCLUDE FOLLOWING IN YOUR <HEAD> ***************** -->

</head>
<!-- End of Head -->

<!-- Begin of Body -->  
<body class="<?=$bodyClass?>"   oncontextmenu="return false">
  <div id="fb-root"></div>
   <?php 
   echo $this->messages->getMessage();?>
    <!-- HEAD START HERE -->   
    <div class="head">
      <div class="navbar navbar-inverse">
            <div class="navbar-inner">
                <div class="container">                   
                    <div class="nav-collapse collapse clearfix">
                        
                      <ul class="nav login-signup-fb-wp">
                        
                         <?php if(!loggedId()){ ?>
                        <li>
                         
                            <a rel="nofollow" class=" " href="javascript:void(0)"  onclick="login('<?=BASEURL?>userTask/login/fblogin',630,700)">
                            <span class="facebook small icon-facebook" ><?=lang('login_btn')?> </span>
                            </a>
                            
                         
                         <!--  <a href="<?=BASEURL.'userTask/logout'?>" id="logout-me" >
                            <?=lang('logout')?>                         
                          </a> -->
                         
                        </li>
						<li>
							 <a rel="nofollow" class=" " href="javascript:void(0)"  onclick="login('<?=BASEURL?>userTask/login/fwlogin',630,700)">
                            <span class="fiware small icon-fiware" >FIWARE </span>
                            </a>
						</li>
                         <?php } ?>
                      </ul>
                </div><!--/.nav-collapse -->
              </div>
            </div>
          </div>
    </div><!--/header -->
    <!-- HEAD END HERE --> 

 

      <div id="container" class="relativeClass">
      <?php
       
        echo $viewContent;
      ?>
      </div>

</body>
<!--/ Body End -->

</html>
