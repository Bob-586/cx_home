<?php

/**
 * @copyright (c) 2015
 * @author Chris Allen, Robert Strutts
 */
?>

<form id="all">
      Name: <input type="text" name="fname" id="fname">
      </form>
      <button type="button" id="go">Submit</button>
      
<script type="text/javascript">
$("#go").click(function() { 
    var name = $("#fname").val();         
    $.ajax({
        url: "<?php echo $this->get_url('app/testing', 'ajax_name'); ?>",
        type: "POST",
        data: { name: name}, // $("#all").serialize()
        success: function (result) {
          alert(result);
        },
        error: function (opps) {
         alert("Error: " + opps);
        }
    });  

    });

</script>