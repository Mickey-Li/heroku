<!DOCTYPE html>
<?php
    setlocale(LC_ALL,'en_US.UTF-8');

    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824){
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }elseif ($bytes >= 1048576){
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }elseif ($bytes >= 1024){
            $bytes = number_format($bytes / 1024, 2) . ' kB';
        }elseif ($bytes > 1){
            $bytes = $bytes . ' bytes';
        }elseif ($bytes == 1){
            $bytes = $bytes . ' byte';
        }else{
            $bytes = '0 bytes';
        }
        return $bytes;
    }    
    
    if( isset($_FILES['userfile']) ){
        $errors= array();
        $file_name = $_FILES['userfile']['name'];
        $file_size = $_FILES['userfile']['size'];
        $file_tmp = $_FILES['userfile']['tmp_name'];

        $allow_extension = array("png", "jpeg", "jpg", "gif", "txt", "csv");
        $file_extension = pathinfo($file_name,PATHINFO_EXTENSION);
        if( !in_array($file_extension,$allow_extension) ){
            $errors[]="Extension not allowed, please choose a jpeg、jpg、png、gif、txt、cvs file.";
        }

        if($file_size > 2097152) {
            $errors[]='File size must be excately 2 MB';
        }
        if ( empty($errors) ){
            move_uploaded_file($file_tmp,"upload/" . $file_name);
        }else{
            move_uploaded_file($file_tmp,"upload/" . $file_name);
        }
    }
    $file_list = array_filter(glob('upload/*'), 'is_file');
?>
<html>
    <head>
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="css/lyc.css">
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script type="text/javascript" src="js/materialize.min.js"></script>
        <script src="js/jquery.matchHeight.js" type="text/javascript"></script>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
        <script src="//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>        
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <style>

        </style>
    </head>
    <body>    
        <div class="row" style="padding:30px;"> 
            <div class="col s12">
                <form enctype="multipart/form-data" method="post" id="upload_form">
                    <div class="form-group">
                        <div class="input-group">
                            <label class="input-group-btn">
                                <span class="btn">
                                    檔案上傳<input type="file" id="userfile" name="userfile" style="display: none;" accept=".jpeg,.jpg,.png,.gif,.csv,.txt">
                                </span>
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <?php foreach (array_reverse($file_list) as $file_name):?>
                <?php $file_extension = pathinfo($file_name,PATHINFO_EXTENSION);?>
                <?php if ( $file_extension != 'csv' ): ?>
                <div class="col s2">
                    <div class="card">
                        <div class="card-content">
                            <span class="card-title activator grey-text text-darken-4"><?php echo pathinfo($file_name,PATHINFO_FILENAME);?><i class="material-icons right">more_vert</i></span>
                            <div class="show_panel">
                                <?php                                
                                    switch ( $file_extension ){
                                        case 'jpeg':
                                        case 'jpg':
                                        case 'png':
                                        case 'gif':
                                            echo "<img src='" . $file_name ."'>";
                                            break;
                                        case 'txt':
                                            echo "<pre style='color:black;'>" . file_get_contents($file_name) . "</pre>";
                                            break;
                                        default:
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="card-reveal">
                            <span class="card-title grey-text text-darken-4">Information<i class="material-icons right">close</i></span>
                            <p>
                                File Name : <?php echo pathinfo($file_name,PATHINFO_FILENAME); ?>
                            </p>
                            <p>
                                File Extension : <?php echo pathinfo($file_name,PATHINFO_EXTENSION); ?>
                            </p>
                            <p>
                                File Size : <?php echo formatSizeUnits(filesize($file_name)); ?>
                            </p>
                            <p>
                                Upload Time : <?php echo "" . date ("Y-m-d H:i:s.", filemtime($file_name)); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <? else:?>
                <div class="col s12">
                    <div class="card">
                        <div class="card-content">
                            <span class="card-title activator grey-text text-darken-4"><?php echo pathinfo($file_name,PATHINFO_FILENAME);?><i class="material-icons right">more_vert</i></span>
                            <div class="show_panel">
                                <table>
                                    <?php
                                        $row = 1;
                                        if (($handle = fopen($file_name, "r")) !== FALSE) {
                                            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                                $num = count($data);
                                                $row++;
                                                echo "<tr><td>" . ($row == 2 ? "" : "#" . ($row-2)) . "</td>";
                                                for ($c=0; $c < $num; $c++) {
                                                    echo "<td>" . $data[$c] . "</td>";
                                                }
                                                echo "</tr>";
                                            }
                                            fclose($handle);
                                        }
                                    ?>
                                </table>
                            </div>
                        </div>
                        <div class="card-reveal">
                            <span class="card-title grey-text text-darken-4">Information<i class="material-icons right">close</i></span>
                            <p>
                                File Name : <?php echo pathinfo($file_name,PATHINFO_FILENAME); ?>
                            </p>
                            <p>
                                File Extension : <?php echo pathinfo($file_name,PATHINFO_EXTENSION); ?>
                            </p>
                            <p>
                                File Size : <?php echo formatSizeUnits(filesize($file_name)); ?>
                            </p>
                            <p>
                                Upload Time : <?php echo "" . date ("Y-m-d H:i:s.", filemtime($file_name)); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </body>
    <script>
        $(function(){
            $(document).on('change', ':file', function() {
                $('#upload_form').submit();
            });
            $(':file').on('fileselect', function(event, numFiles, label) {
                console.log("b");
                var input = $(this).parents('.input-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' files selected' : label;
                input.val(log);
            });
        });
    </script>
</html>