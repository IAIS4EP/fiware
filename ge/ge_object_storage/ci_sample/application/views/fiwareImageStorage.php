
<html>
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script type="text/javascript" src="http://54.213.242.73/objstorage/default/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
	<link rel="stylesheet" href="http://54.213.242.73/objstorage/default/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
	<script type="text/javascript">
		$(document).ready(function (e) {
			$(".show-image").click(function(){
				getImage($(this).attr('filename'));
			});
		});
		/*
		 Requires Browsers versions: Firefox: 13.0+ Chrome: 20+ Internet Explorer: 10.0+ Safari: 6.0 Opera: 12.10
		 */
		function getImage(fileName) {
		
			var data = new FormData();
			data.append('fileName', fileName);
			data.append('action', 'get');
			$.ajax({
				url: "<?=base_url()?>"+"index.php/image_storage/getimage",
				type: "POST",
				data:  data, // Form fields and values
				contentType: false, // The content type used when sending data to the server. Default is: "application/x-www-form-urlencoded"
				cache: false, // Disable request pages caching
				processData:false, // Prevent data processing (we need raw image data)
				dataType: "binary", // Get the response as Binary
				responseType: 'arraybuffer',
				success: function(imageData)
				{
				
					var arr = new Uint8Array(imageData);
					
					// Convert the int array to a binary string
					// We have to use apply() as we are converting an *array*
					// and String.fromCharCode() takes one or more single values, not
					// an array.
					var raw= String.fromCharCode.apply(null, arr);
				
					// convert to BASE64 and set image
					var b64=btoa(raw);
					var dataURL="data:image/jpeg;base64,"+b64;
					
					$(".fancybox").attr('href', dataURL);
					$(".fancybox").fancybox();
					$('#image-display').attr('src',dataURL);
					
				}
			});
		}
		// binary ajax transport plugin for "binary" jQuery dataType
		$.ajaxTransport("+binary", function(options, originalOptions, jqXHR){
			// check for conditions and support for blob / arraybuffer response type
			if (window.FormData && ((options.dataType && (options.dataType == 'binary')) || (options.data && ((window.ArrayBuffer && options.data instanceof ArrayBuffer) || (window.Blob && options.data instanceof Blob)))))
			{
				return {
					// create new XMLHttpRequest
					send: function(headers, callback){
						// setup all variables
						var xhr = new XMLHttpRequest(),
							url = options.url,
							type = options.type,
							async = options.async || true,
						// blob or arraybuffer. Default is blob
							dataType = options.responseType || "blob",
							data = options.data || null,
							username = options.username || null,
							password = options.password || null;
						xhr.addEventListener('load', function(){
							var data = {};
							data[options.dataType] = xhr.response;
							// make callback and send data
							callback(xhr.status, xhr.statusText, data, xhr.getAllResponseHeaders());
						});
						xhr.open(type, url, async, username, password);
						// setup custom headers
						for (var i in headers ) {
							xhr.setRequestHeader(i, headers[i] );
						}
						xhr.responseType = dataType;
						xhr.send(data);
					},
					abort: function(){
						jqXHR.abort();
					}
				};
			}
		});
	</script>
</head>
<body> <? echo $status;?>
<div style='background-color:#dfdfdf; border-width:thin; border-color:#333333; border-style:solid;padding:5px; margin:5px;'>
	<big>Upload new file</big> <small>(File should not be greater than 400KB)</small>
	<form action="<?=base_url()?>index.php/image_storage" method="post" enctype="multipart/form-data">
		<input type="hidden" name="action" id="action" value="upload"><br>
		<input type="file" name="file" id="file"><br>
		<input type="submit" name="submit" value="Upload ">
	</form>
</div>
<br />
<table>
	<tr>
		<td>
			<div style='background-color:#dfdfdf; border-width:thin; border-color:#333333; border-style:solid;padding:5px; margin:5px; height:800px; overflow: scroll;'>
				<?foreach ($files as $fileDesc) {
				$filename = $fileDesc["name"];?>
				<form action="<?=base_url()?>index.php/image_storage" method="post" enctype="multipart/form-data">
								<input type='hidden' name='action' value='remove'>
								<input type='hidden' name='fileName' value='<?=$filename?>'>
								<b><?=$filename?></b> <a href="#" class="show-image" filename='<?=$filename?>'>Show</a> 
								<input type="submit" name="submit" value="X">
								<br />
						</form>
			<?}?>
			</div>
		</td>
		
		<td valign="top">
			<div style="border:1px;">
			<a id="single_image"  class="fancybox" href="">
				<img id="image-display" src="" style="width: auto; height: auto;">
			</a>
			</div>
		</td>
		
	</tr>
</table>
</body>
</html>
