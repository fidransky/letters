$.fn.html5uploader = function(options){
    var info = $('#info');
    var progressbar = $('#progress div'); 
    var result = $('#result');
    var destinationUrl = options.pluginsDir +'soubory/uploader/uploader.php?ajax';
    var totalSize = 0;
    var totalProgress = 0;
    
    var settings = {
        'appendToUrl': '',
        'maxFileSize': 1024,  // filesize in kilobytes
        'maxFileSizeExceeded': '<p class="error">Soubor &bdquo;%s&ldquo; je příliš velký.</p>',
        'allowedFileExtensions': '',
        'notAllowedFileExtension': '<p class="error">Soubory typu %s jsou zakázány.</p>',
        'incompatibleBrowser': 'Incompatible browser'
    };
    
    var options = $.extend(settings, options);
    
    return this.each(function(){

    // update progress
    function handleProgress(e) {
        var progress = totalProgress + e.loaded;
        drawProgress(progress / totalSize);
    }

    // on complete
    function handleComplete(size) {
        totalProgress += size;
        drawProgress(totalProgress / totalSize);
    }

    // draw progress
    function drawProgress(progress) {
        progressbar.html(Math.floor(progress * 100) + '%');
        progressbar.css('width', progress * 500);
    }

    // upload file
    function uploadFile(file) {
        // prepare XMLHttpRequest
        var xhr = new XMLHttpRequest();
        
        xhr.onload = function() {
            result.append(this.responseText);
            handleComplete(file.size);
        };
        xhr.onerror = function() {
            result.html(this.responseText);
            handleComplete(file.size);
        };
        xhr.upload.onprogress = function(e) {
            handleProgress(e);
        };
        xhr.upload.onload = function() {
        }
        
        // XmlHttpRequest 2 (support: http://caniuse.com/#feat=xhr2)
        if (window.FormData) {
            xhr.open('POST', destinationUrl, true);

            var formData = new FormData();
            formData.append('soubory[]', file);
            xhr.send(formData);
        }
        // Firefox 3.6 provides a feature sendAsBinary() -> we can use the array $_FILES
        else if (file.getAsBinary) {
            xhr.open('POST', destinationUrl, true);

            var boundary = '0123456789';
            var dashes = '--';
            var crlf = '\r\n';
              
            /* Build RFC2388 string. */
            var data = '';

            data += dashes + boundary + crlf;

            data += 'Content-Disposition: form-data; name="' + file.name + '"; filename="' + unescape(encodeURIComponent(file.name)) + '"' + crlf;
            data += 'Content-Type: application/octet-stream' + crlf + crlf;
  
            data += file.getAsBinary() + crlf;

            data += dashes + boundary + dashes + crlf;
            
            xhr.setRequestHeader('Content-Type', 'multipart/form-data; boundary=' + boundary);
            xhr.sendAsBinary(data);
        }
        // Chrome 7 sends data but you must use the base64_decode on the PHP side
				else if (window.btoa) {
            xhr.open('POST', destinationUrl +'&base64', true);
            
            xhr.setRequestHeader('X-Filename', file.name);
			      xhr.setRequestHeader('X-Size', file.size);
				    xhr.setRequestHeader('X-Type', file.type);
				        
            xhr.send(window.btoa(file.getAsBinary()));
				}
				// incompatible browser
				else
            result.html(options.incompatibleBrowser);
    }

    // initialize handlers
    var thiz = $(this);    
    
    thiz.bind('dragenter dragover', function(e){
        e.stopPropagation();
        e.preventDefault();
        $(this).addClass('hover');
    }).bind('dragleave', function(e){
        e.stopPropagation();
        e.preventDefault();
        $(this).removeClass('hover');
    }).bind('drop', function(e){
        e.stopPropagation();
        e.preventDefault();
        $(this).removeClass('hover');
        info.show();
        $('p.success, p.info, p.error').remove();
        
        var files = e.originalEvent.dataTransfer.files;
        if (!files.length) return;
     
        totalSize = 0;
        totalProgress = 0;

        for (var i = 0; i < files.length && i < 5; i++) { totalSize += files[i].size; }
        
        for (var i = 0; i < files.length && i < 5; i++) {
            var upType = $('input[name=up_type]:checked').val();
            var extension = files[i].name.substring(files[i].name.lastIndexOf('.') + 1).toLowerCase();
            
            if (files[i].size >= (options.maxFileSize * 1024)) {
                result.append(options.maxFileSizeExceeded.replace('%s', files[i].name));
                handleComplete(files[i].size);
            }
            else if (upType != 'system' && jQuery.inArray(extension, options.allowedFileExtensions.split(', ')) == -1) {
                result.append(options.notAllowedFileExtension.replace('%s', extension.toUpperCase()));
                handleComplete(files[i].size);
            }
            else {
                destinationUrl += '&i='+ i +'&count='+ files.length;
                destinationUrl += '&up_type='+ upType;
                destinationUrl += '&as_what='+ $('input[name=as_what]:checked').val();
                destinationUrl += options.appendToUrl;
                if (typeof plugins !== 'undefined') destinationUrl += plugins;
                uploadFile(files[i]);
            }
        }
    });
    
    });
}