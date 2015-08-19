<h1 id="AdminListHeader">Administrator Account List</h1>
<button class="btn btn-success btn-sm" name="addadmin" type="button" value="Add New Administrator" onclick="document.location='account.php?cmd=admin-edit&amp;aid=0'"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Add New Administrator</button>

        <div class="wrapper"><div class="container"><div class="row">

<div class="panel panel-default top20">
<div class="panel-body">
<table id="AdminList" class="table table-striped table-hover">
  <thead>
    <tr>
			  <th>ID</th>
				<th>Data</th>
		</tr>
  </thead>
  <tbody>
                    
  </tbody>
</table>
</div>
</div>

        </div></div></div>            
            
<script>
$( document ).ready(function() {
	var oldStart = 0;
   	$('#AdminList').dataTable({				
			"order": [[ 1, "asc" ]],
			"iDisplayLength": 25,
			"processing": true,
        "serverSide": true,
        "ajax": "<?php echo $this->get_url('/app/testing', 'ajax_ssp', $q); ?>",
			"oLanguage": {            
            "sEmptyTable": "There are no admins to display."
        },	
			 "fnDrawCallback": function (o) {
            if ( o._iDisplayStart != oldStart ) {
                var targetOffset = $('#AdminListHeader').offset().top;
                $('html,body').animate({scrollTop: targetOffset}, 100);
                oldStart = o._iDisplayStart;
            }
        }
		});
		
	

	$("#AdminList").fadeIn(500);
	
});

</script>
                    