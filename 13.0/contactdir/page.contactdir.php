<?php
if (!defined('FREEPBX_IS_AUTH')) { die('No direct script access allowed');}
	$edit = $_GET['edit'];
	
	$servertype = false;
	$servername = false;
	$serverhost = false;
	$serverport = false;
	$serverssl = false;

	// Handle adding/updating an engine
	if ($_POST['delete']){
		contactdir_delete_server($_POST['serverid']);
	}else if ($_POST['edit']){
		contactdir_update_server($_POST['serverid'], $_POST['servertype'], $_POST['servername'], $_POST['serverhost'], $_POST['serverport'], $_POST['serverssl']);
	}else if ($_POST['addserver']){
		contactdir_add_server($_POST['servertype'], $_POST['servername'], $_POST['serverhost'], $_POST['serverport'], $_POST['serverssl']);
	}

	$servers = contactdir_get_all_servers();
	$server_types = contactdir_get_all_server_types();
?>

<h2>Contact Directory Servers</h2>

<!-- right side menu -->
<div class="rnav">
	<ul>
		<li><a href=?type=tool&display=contactdir>Add Server</a></li>
		<hr>
		<?php
			foreach($servers as $server)
			{
				if (($edit) && ($server['id'] == $edit))
				{
					$servertype = $server['server_type'];
					$servername = $server['display_name'];
					$serverhost = $server['host'];
					$serverport = $server['port'];
					$serverssl = $server['usessl'];
				}
				echo '<li><a href=?type=tool&display=contactdir&edit=' . $server['id'] . '>' . $server['display_name'] . '</a></li>';
			}
		?>
	<ul>
</div>


<div class="content">
	<form action="" method="post">
		<p>
			On this page you can manage servers that users can sync contacts from.
 		</p>
		<span id="options" <?php echo(($servertype == '1')?"style='display: none;'":""); ?>>
		<?php 
			if ($edit)
			{
				echo '<input type="hidden" name="edit" value="1">';
				echo '<input type="hidden" name="serverid" value="' . $edit . '">';
			}
			else
			{
				echo '<input type="hidden" name="addserver" value="1">';
			}
		?>

		<p>
			<a href=# class="info"><?php echo _("Type")?><span><br><?php echo _("Please choose what version of Exchange you would like to connect to.")?><br><br></span></a>: 
			<select name="servertype">
				<?php
					foreach ($server_types as $server_type){
						if ($server_type['id'] != '1'){ //dont print local db
							if ($server_type['id'] == $servertype){
								$selected = 'selected';
							}else{
								$selected = '';
							}
							print '<option value="' . $server_type['id'] . '" ' . $selected . '>' . $server_type['server_type'] . "</option>";
						}
					}	
				?>
			</select>
			
			<script>
			$(document).ready(function() {
				$('select[name=servertype]').change(function(){
					if ($(this).val() == '1'){
						$('#options').hide();
						$('#noopts').show();
				} else if ($(this).val() > '1'){
					$('#options').show();
					$('#noopts').hide();
				}
			});
			});
			</script>
			<br>
		</p>
		<p>
			<a href=# class="info"><?php echo _("Name")?><span><br><?php echo _("Please name this connection to differeniate between Exchange servers if you have mutlple servers setup.")?><br><br></span></a>: <input type=text name=servername value="<?php echo $servername ?>"><br>
		</p>
	
		<p>
			<a href=# class="info"><?php echo _("Host")?><span><br><?php echo _("Enter the full url, including http/https, as you would would when access your Exchange server through OWA.")?><br><br></span></a>: <input type=text name=serverhost value=<?php echo $serverhost ?>><br>
		</p>

		<p>
			<input name=submit type=submit value=Submit> <?php if ($edit) { echo "<input name=delete type=submit value=Delete>"; } ?>
		</p>
		</span>

		<span id="noopts" <?php echo(($servertype == '1')?"":"style='display: none;'")?>>
		There are no options for Local DB.
		</span>
	</form>
</div>
