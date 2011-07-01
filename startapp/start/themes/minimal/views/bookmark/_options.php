<div class="optionsTitle"><?php echo CHtml::encode($model->title);?></div>
<div class="optionsButtons">
    <div class="optionsEditImage"><span class="mainButtonText">Edit</span></div>
    <div class="optionsDelete"><span class="mainButtonText">Delete</span></div>
<div class="message" type="hidden"></div>

</div>

<script type="text/javascript">

$(document).ready(function(){
    $(".optionsDelete").click(function(){

        gotID = $(this).closest('.tooltip').attr('id');
        var loadUrl = "index.php?r=bookmark/delete&id="+gotID+""; 
        // to prevent error message when mousecursor fires event to load 
        // getBookmarkTitle again
        $('#'+gotID+'').unbind();
        $('.message').load(loadUrl,{'id':gotID},function(response, status, xhr) {
            if (status == "error") {
                var msg = "error deleting: ";
                $(this).html(msg + xhr.status + " ");
                }
            if (status == "success") {
                $(this).closest('.tooltip').hide();
                $('#'+gotID+'').prev().slideUp(500,function(){
                    $(this).next().remove();
                    $(this).remove();
                });
            }
        });
    });
    // click on Edit Button within headerbox is generating img tag and loading  
    // the image from the BookmarkController (getBigImage). The given id from 
    // the clicked object is recognized by "gotID".
    // Then the img tag, named as id=bigImage will be extended by the axzoomer
    // to provide the pan and zoom ability. 
    // Finally the coordinates are written in form elements to achieve a ajax
    // call to the serverside. 
    $(".optionsEditImage").click(function(){
        $("#dialogEditImage").dialog('open');
        gotID = $(this).closest('.tooltip').attr('id');
        var loadUrl = "index.php?r=bookmark/GetBigImage&id="+gotID+"";
        $("#dialogEditImage").append('<img id="bigImage" style="width: 640px; height: 480px;"/>');
        $("#bigImage").attr('src',loadUrl);
        $("#bigImage").axzoomer({
            'maxZoom':4,
            'sensivity':10,
            'reset':false
        });
        // setting id to the hidden form when user wants to crop it
        $('input[name=id]').val(gotID);
            
        // setting the coordinates to the hidden form elements in index.php
        function setCoord() {
            $('input[name=top]').val($("#bigImage").css("top"));
            $('input[name=left]').val($("#bigImage").css("left"));
            $('input[name=width]').val($("#bigImage").css("width"));
            $('input[name=height]').val($("#bigImage").css("height"));
        };
        
        // call the setCoord() when mousewheel is being used on the Image
        $("#bigImage").mousewheel(function() {
            setCoord();
        });
        // call the setCoord() when mouse button is going up on the Image
        $("#bigImage").mouseup(function(){
            setCoord();
        });
        
    });
    
    $('input[name=submit]').click(function(){
        $("#dialogEditImage").dialog('close');
    });
    
    
});

</script>

