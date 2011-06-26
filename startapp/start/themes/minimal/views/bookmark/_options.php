<div class="optionsTitle"><?php echo CHtml::encode($model->title);?></div>
<div class="optionsButtons">
    <div class="optionsEditImage">Edit</div>
    <div class="optionsDelete">Delete</div>
<div class="message">for info</div>
</div>

<script type="text/javascript">

$(document).ready(function(){
    $(".optionsDelete").click(function(){
        
        gotID = $(this).closest('.tooltip').attr('id');
        var loadUrl = "index.php?r=bookmark/delete&id="+gotID+""; 
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
    
    $(".optionsEditImage").click(function(){
        $("#dialogEditImage").dialog('open');
        var loadUrl = "index.php?r=bookmark/GetBigImage";
        gotID = $(this).closest('.tooltip').attr('id');
        $("#dialogEditImage").html(ajax_load).load(loadUrl,{'id':gotID},function(response, status, xhr) {
            if (status == "error") {
                var msg = "error loading image: ";
                $(this).html(msg + xhr.status + " ");
            }
        });
    });
});

</script>

