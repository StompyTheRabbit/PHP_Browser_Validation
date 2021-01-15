<?php
session_start();
$domain = "localhost";

$Session_ID = md5($_SERVER['REMOTE_ADDR']."-".$_SERVER['HTTP_USER_AGENT']);
if(!(isset($_SESSION['website_ddos_clearance'])) || $_SESSION['website_ddos_clearance'] != $Session_ID)
{
  if(isset($_SESSION['website_ddos_math_answer']) && $_SESSION['website_ddos_math'])
  {
    $math_answer = $_SESSION['website_ddos_math_answer'];
    $math = $_SESSION['website_ddos_math'];
  }
  else
  {
    $math_first = rand(11111111, 99999999);
    $math_second = rand(11111, 99999);
    $math_third = rand(111, 999);
    $math = $math_first."+".$math_second."*".$math_third;
    $math_answer = $math_first + $math_second * $math_third;
    $_SESSION['website_ddos_math_answer'] = $math_answer;
    $_SESSION['website_ddos_math'] = $math;
  }

  if(isset($_POST['Form_Submit']) && $_POST['Form_Submit'] == "Form_Submit")
  {
    if($_POST['Session_ID'] == $Session_ID && $_SERVER['HTTP_HOST'] == $domain)
    {
      if($_POST['DDoS_Answer'] == $math_answer)
      {
        unset($_SESSION['website_ddos_math_answer']);
        unset($_SESSION['website_ddos_math']);
        $_SESSION['website_ddos_clearance'] = $Session_ID;
        header("Location: ".$_SERVER['PHP_SELF']."");
        die();
      }
      else
      {
        unset($_SESSION['website_ddos_math_answer']);
        unset($_SESSION['website_ddos_math']);
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>DDoS Protection by ExoCDN</title>
        <link rel="stylesheet" media="screen" href="js-challenge/inc/js-challenge.css" type="text/css">
        <link rel="stylesheet" media="screen" href="js-challenge/inc/progress_bar.css" type="text/css">
    </head>
    <body>
        <div id="outer">
            <div id="container">
                <div id="inner">
                    <div id="portcdn_title">DDoS Protection by ExoCDN</div>
                    <noscript>You must enable JavaScript before accessing this site.</noscript>
                    <div id="portcdn_notify">
                        <div id="portcdn_sub">
                            Validating your browser before accessing:
                            <br /><b><?php echo $_SERVER['HTTP_HOST']; ?></b>.
                        </div>
                        <div id="portcdn_sub">This should take no longer than 5 seconds.</div>
                        <div id="circle_loader">
                            <div class="pie_progress" role="progressbar" data-goal="100" aria-valuemin="0" aria-valuemax="100">
                              <div class="pie_progress__number">0%</div>
                              <div class="pie_progress__label">Progress</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Start | DDoS Check -->
        <form id="Website_ChallengeForm" action="" method="POST">
            <input type="hidden" name="Form_Submit" value="Form_Submit" />
            <input type="hidden" name="Session_ID" value="<?php echo $Session_ID; ?>" />
            <input type="hidden" id="DDoS_Answer" name="DDoS_Answer" />
        </form>
        <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
        <script type="text/javascript" src="js-challenge/inc/progress_bar.js"></script>
        <script>
        jQuery(document).ready(function($){
            $("#portcdn_notify").show();
            $('.pie_progress').asPieProgress({
                namespace: 'pie_progress'
            });

            countValue = 0;
            setInterval( function(){
                if(countValue >= 100)
                {
                    $('.pie_progress').asPieProgress('go', 100);
                }
                else
                {
                    $('.pie_progress').asPieProgress('go', countValue);
                    countValue += 1;
                }
            }, 50);

            $(function(){setTimeout(
               function(){
                   $('#DDoS_Answer').val(<?php echo $math; ?>);
                   $('#Website_ChallengeForm').submit();
               },
               5850
            )});
        });
        </script>
        <!-- End | DDoS Check -->
    </body>
</html>
<?php
    die();
}
?>
