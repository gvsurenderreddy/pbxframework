<?php
if (!defined('FREEPBX_IS_AUTH')) { die('No direct script access allowed');}
	$edit = $_GET['edit'];
	$enginename = false;
	$enginepath = false;
	// Handle adding/updating an engine
	if ($_REQUEST['delete'])
	{
		ttsengines_delete_engine($_REQUEST['engineid']);
	}
	else if ($_POST['edit'])
	{
		ttsengines_update_engine($_POST['engineid'], $_POST['enginename'], $_POST['enginepath']);
	}
	else if ($_POST['addengine'])
	{
		ttsengines_add_engine($_POST['enginename'], $_POST['enginepath']);
	}

	$engines = ttsengines_get_all_engines();
	$info = show_help(_('On this page you can manage text to speech engines on your system. When you add an engine you give it a name, and the full path to the engine on your system. After doing this the engine will be available on the text to speech page.'));
	$heading = _('Text to Speech Engines');
	$delurl = '';
	if(isset($edit)){
		$data = \FreePBX::Ttsengines()->getEngine($edit);
		$enginename = isset($data['name'])?$data['name']:'';
		$enginepath = isset($data['path'])?$data['path']:'';
		$delurl = '?display=ttsengines&delete=true&engineid='.$edit.'&edit='.$edit;
	}
?>
<div class="container-fluid">
	<h1><?php echo $heading?></h1>
		<?php echo $info?>
	<div class = "display full-border">
		<div class="row">
			<div class="col-sm-9">
				<div class="fpbx-container">
					<div class="display full-border">
						<form class="fpbx-submit" name="frm_ttsengines" action="" method="post" data-fpbx-delete="<?php echo $delurl?>" role="form">
							<?php
								if ($edit)
								{
									echo '<input type="hidden" name="edit" value="1">';
									echo '<input type="hidden" name="engineid" value="' . $edit . '">';
								}
								else
								{
									echo '<input type="hidden" name="addengine" value="1">';
								}
								?>
								<!--Engine Name-->
								<div class="element-container">
									<div class="row">
										<div class="col-md-12">
											<div class="row">
												<div class="form-group">
													<div class="col-md-3">
														<label class="control-label" for="enginename"><?php echo _("Engine Name") ?></label>
														<i class="fa fa-question-circle fpbx-help-icon" data-for="enginename"></i>
													</div>
													<div class="col-md-9">
														<select class="form-control" id="enginename" name="enginename" data-value="<?php echo isset($enginename)?$enginename:''?>">
															<option value="text2wave" <?php echo !empty($enginename) && $enginename == "text2wave" ?'selected':''?>>text2wave</option>
															<option value="flite" <?php echo !empty($enginename) && $enginename == "flite" ?'selected':''?>>flite</option>
															<option value="swift" <?php echo !empty($enginename) && $enginename == "swift" ?'selected':''?>>swift</option>
															<option value="pico" <?php echo !empty($enginename) && $enginename == "pico" ?'selected':''?>>pico</option>
															<option value="custom" <?php echo !empty($enginename) && !in_array($enginename,array("text2wave","flite","swift","pico")) ?'selected':''?>>custom</option>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<span id="enginename-help" class="help-block fpbx-help-block"><?php echo _("The name you enter will be shown in a drop down box on the Text to Speech page so you can select which engine you want to play your")?></span>
										</div>
									</div>
								</div>
								<!--END Engine Name-->
								<!--Engine Path-->
								<div class="element-container">
									<div class="row">
										<div class="col-md-12">
											<div class="row">
												<div class="form-group">
													<div class="col-md-3">
														<label class="control-label" for="enginepath"><?php echo _("Engine Path") ?></label>
														<i class="fa fa-question-circle fpbx-help-icon" data-for="enginepath"></i>
													</div>
													<div class="col-md-9">
														<input type="text" class="form-control" id="enginepath" name="enginepath" value="<?php echo isset($enginepath)?$enginepath:''?>">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<span id="enginepath-help" class="help-block fpbx-help-block"><?php echo _("The full path to the binary of your text to speech engine. For example: /usr/sbin/magic_speech_engine.")?></span>
										</div>
									</div>
								</div>
								<!--END Engine Path-->
						</form>
					</div>
				</div>
			</div>
			<div class="col-sm-3 hidden-xs bootnav">
				<div class="list-group">
					<a href=?type=tool&display=ttsengines class="list-group-item <?php echo isset($edit)?'':'active'?>"><i class="fa fa-plus"></i> <?php echo _('Add Engine')?></a>
					<?php
						foreach($engines as $engine)
						{
							$active = '';
							if ($edit && $engine['id'] == $edit)
							{
								$active = ' active ';
							}
							echo '<a href="?type=tool&display=ttsengines&edit=' . $engine[id] . '" class="list-group-item '.$active.'">' . $engine[name] . '</a>';
						}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
