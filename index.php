# RTcamp_FB_Assignment


<html>
<head>

<title>Login with Facebook for Image Downloader</title>
<script type="text/javascript" src="lib/js/jquery-1.10.0.min.js"></script>
<link rel="stylesheet" href="lib/css/bootstrap.css" />
<link rel="stylesheet" href="lib/css/bootstrap.min.css" />
<link rel="stylesheet" href="lib/css/style.css" />
<!--<link rel="stylesheet" href=">-->
<script type="text/javascript" src="lib/js/bootstrap.js"></script>
<script type="text/javascript" src="lib/js/bootstrap.min.js"></script>





</head>


<body class="bodycss">

<?php
//All configuration files will be loaded
include_once ("include.php");

use Facebook\FacebookSession;

use Facebook\FacebookRedirectLoginHelper;

use Facebook\FacebookRequest;

use Facebook\FacebookResponse;

use Facebook\FacebookSDKException;

use Facebook\FacebookRequestException;

use Facebook\FacebookAuthorizationException;

use Facebook\GraphObject;


try {
//creation of a sessionss
	if (isset ( $session )) {
		$_SESSION ['fb_login_session'] = $session;

		$_SESSION ['fb_token'] = $session->getToken ();

		
		$userinfo = getdatafromfaceboook ( "/me" );

		$_SESSION ['user_id'] = $userinfo ['id'];
		$_SESSION ['username'] = $userinfo ['name'];
		
		?>
		
    <div class="container" style="margin-top: 0px; padding-top: 0px">
		<div class="header " style="padding-bottom: 5px">
			<nav>
			<ul class="nav nav-pills pull-right">
				<li class="" role="presentation"><span><img class="img-circle"
						src="https://graph.facebook.com/<?php echo $userinfo['id']; ?>/picture"
						style="margin-right: 10px" alt="" /> <label
						style="margin-right: 10px"><?php echo $userinfo['name']; ?></label>
				</span></li>
				<li class="active"><a href="logout.php">Logout</a></li>
			</ul>
			</nav>
			<h2 class="text-muted">Welcome to Image Downloader</h2>
		</div>
		<hr>
			<hr>
				<div class="modal fade" id="download-modal" tabindex="-1"
					role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">
									<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
								</button>
								<h4 class="modal-title" id="myModalLabel">Albums Report</h4>
							</div>
							<div class="modal-body" id="display-response">
								<!-- Response is displayed over here -->
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default"
									data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
				<div id="progressbar" class="progress hide">
					<div id="progressbar1" class="progress-bar" role="progressbar"
						aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"
						style="width: 1%;"></div>
				</div>
			
				<div class="row">
      <?php
		$albums = getdatafromfaceboook ( "/me/albums" );
		
		if (! empty ( $albums )) {
			
			foreach ( $albums ['data'] as $album ) {
				$album = ( array ) $album;
				
				$album_photo = getdatafromfaceboook ( '/' . $album ['id'] . '/photos?fields=source' );
				if (! empty ( $album_photo )) {
					foreach ( $album_photo ['data'] as $alb ) {
						$alb = ( array ) $alb;
					}
					
					?>
                    <div class="col-sm-5 col-md-2">
						<span> <a href="home.php?ida=<?php echo $album['id']; ?>"><?php echo $album['name']; ?></a>
						</span>

						<div class='thumbnail'
							style='overflow: hidden; height: 170px; width: 190px; padding-bottom: 2%;'>
							<div
								style='overflow: hidden; height: 160px; width: 180px; padding-bottom: 2%;'>
								<a href="slide.php?ida=<?php echo $album['id']; ?>"> <img
									style='box-shadow: 2px 2px 1.5px 1.5px #9d9d9d; position: relative;'
									src="<?php  echo $alb['source']; ?> " /></a>
							</div>
						</div>
						<div class="form-control input-group" style="margin-left: 2%;">
							<span class="input-group-addon input-sm"> <input
								class="select-album" type="checkbox"
								value="<?php echo $album['id'].','.$album['name'];?>" />
							</span>
							<button rel="<?php echo $album['id'].','.$album['name'];?>"
								class="single-download btn btn-default btn-sm"
								title="Download Album">
								<span class="glyphicon glyphicon-save" aria-hidden="true"></span>
							</button>
							<button type="button" class="btn btn-default btn-sm">
								<span class="glyphicon glyphicon glyphicon-share"
									aria-hidden="true">Move</span>
							</button>
						</div>
					</div>
                      <?php
				}
			}
		}
	} else {
		
		?>        






           <table  border="0"  align="center" width="100%" cellspacing="30">

	<tr>

		<td colspan="4" align="center">

		
		<font color="red" size="6"> 

		WELCOME TO FACEBOOK IMAGE-DOWNLOADER
		
		</font>
		</td>

	</tr>
	
	<tr>
		<td colspan="4" align="center">
		<font color="red" size="6"> 
	

		<a href="<?php echo $helper->getLoginUrl (array("user_photos","public_profile"));?>">
		Click here to Log In with Facebook
		</a>
		</font>
		</td>
	</tr>

	<tr>
		<td  colspan="4">
			<center><img src="Downloader.png"></center>
		</td>
	</tr>






</table>











<?php
	}
} catch ( Exception $ex ) {
	echo $ex;
}
?>
  </div>

</body>
<script type="text/javascript">
	
	function append_download_link(url) {
					$.ajax({
						url:url,
						success:function(result){
							$("#display-response").html(result);
							$("#download-modal").modal({
								show: true
							});
						}
					});
				}
    
	$("#download-all-albums").on("click", function() {
        append_download_link("download_album.php?zip=1&all_albums=all_albums");

	});
//single download
    				$(".single-download").on("click", function() {
        
					var rel = $(this).attr("rel");
					var album = rel.split(",");

					append_download_link("download_album.php?zip=1&single_album="+album[0]+","+album[1]);
				});

    
    //get selected data/lib
    
    function get_all_selected_albums() {
        
					var selected_albums;
					var i = 0;
					$(".select-album").each(function () {
						if ($(this).is(":checked")) {
							if (!selected_albums) {
								selected_albums = $(this).val();
							} else {
								selected_albums = selected_albums + "/" + $(this).val();
							}
						}
					});
					return selected_albums;
				}
//selected data
    $("#download-selected-albums").on("click", function() {
        
					var selected_albums = get_all_selected_albums();
					append_download_link("download_album.php?zip=1&selected_albums="+selected_albums);
				});
</script>
</html>
