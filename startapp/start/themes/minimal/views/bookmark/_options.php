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
        //alert(gotID);
        var ajax_load = "<img src='themes/minimal/images/load.gif' alt='loading...' />";  
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
});

</script>

