<?php
include_once 'config.php';
$page_title = 'Fee Assignment';
$page_url = 'upload_data_add';


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    
</head>

<body>
    
                                <form id='<?php echo $page_url; ?>'>
                                    
                                    <div class="row">
                                        
                                         <div class="col-lg-4">
                                            <div class="form-group floating-label enable-floating-label">
                                                <input id="file" name="file" type="file" class="form-control">
                                                <div class="file text-danger"></div>
                                            </div>
                                        </div>
                                        
                                               
                                        
                                  
                                    </div>
                                    <!-- This button link with id-sw-default-step-1 if you change it change in serial number like below -->
                                    <div class="clearfix">
                                    <input type='submit' id='submit' name='submit' class='btn btn-primary' value='Submit' />
    						            <a href="http://localhost/feeassignment/upload_data_add.php" class='btn btn-danger m-b-0'>Cancel</a><br>
    						            <div id='response' class="text-danger"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
    </div>
    <!-- Page End -->
    <!-- ================== BEGIN BASE JS ================== -->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <!-- ================== END PAGE JS ================== -->
    <script>
	$(document).ready(function(){
		$('#<?php echo $page_url; ?>').submit(function(){
			// show that something is loading
			//alert("fefge");
			document.getElementById("submit").disabled = true;
			$('#response').html("Loading response...");
			// Call ajax for pass data to other place
			$.ajax({
			type: 'POST',
			url: '<?php echo $page_url; ?>_db.php',
			data : new FormData(this),
			//dataType: 'json',
			contentType:false,
			cache:false,
			processData:false,
			//data: $(this).serialize() // getting filed value in serialize form
			})
			.done(function(data){ // if getting done then call.
			console.log(data);
				if(data.result == 1){
					$('#response').html(data.msg);
				//	window.location.href = data.route;
				}else if(data.result == 0){
					$('#response').html(data.msg);
					$.each(data.error_data,function(key,value){
                       // console.log(value);
						//console.log(value.e);
						$('.'+value.e).html(value.m);
						if(value.m){
							$('#'+value.e).addClass('form-control-danger');
							$('#'+value.e).addClass('is-invalid');
						} else {
							$('#'+value.e).removeClass('form-control-danger');
							$('#'+value.e).removeClass('is-invalid');
						}
						
                    });
				}else { 
					$('#response').html('something went wrong');
				}
				document.getElementById("submit").disabled = false;
			})
			.fail(function() { // if fail then getting message
			// just in case posting your form failed
				$('#response').html( "Posting failed.");
			//	location.reload();
			});
		// to prevent refreshing the whole page page
		return false;
		});
	});


	
</script>
</body>
</html>