<?php
	header( "Content-Type: text/html; charset=UTF-8");
	header( 'Access-Control-Allow-Origin: *');
	import_request_variables("GPC", "");
	ob_start( 'ob_gzhandler');
	session_start();

	$entityId 	= 0;

	include_once "core.php";

	$entityId 	= $_REQUEST["entityId"];
	$type 		= $_REQUEST["type"];

	if ($entityId == null)
	{
		$entityId = $_SESSION["entityId"];
	}

	if ($entityId != null)
	{
		$result 		= getEntity($entityId);
		$type 			= $result["type"];
		$name 			= $result["name"];
		$state	 		= $result["state"];
		$icon 			= $result["icon"];
		$parentId 		= $result["parentId"];
		$sortOrder 		= $result["sortOrder"];
		$nodeReference 	= $result["nodeReference"];
		$code 			= $result["code"];
		$listCode 		= $result["listCode"];
		$data 			= $result["data"];
	}
?>
<div id="formOverlay">
	<form action="" method="post" class="editForm">
		<div class="formContainer">
			<input type="hidden" name="userAction" value="update" />
			<input type="hidden" name="type" value="<?php print $_REQUEST["type"] ?>" />
			<input type="hidden" name="entityId" value="<?php print $entityId ?>" />
			<div class="toggleSystemProperties">System properties</div>
			<div class="systemProperties">
				<div>
					<label for="type">Type</label>
					<div class="linkField"><?php print getEntityName($type) ?></div>
					<input type="hidden" id="type" name="type" value="<?php print $type ?>" />
					<div class="linkButton"></div>
				</div>
				<div>
					<label for="parentId">Parent</label>
					<div class="linkField"><?php print getEntityName($parentId) ?></div>
					<div class="linkButton"></div>
					<input type="hidden" id="parentId" name="parentId" value="<?php print $parentId ?>" />
				</div>
				<div>
					<label for="nodeReference">Node ref</label>
					<div class="linkField"><?php print getEntityName($nodeReference) ?></div>
					<div class="linkButton"></div>
					<input type="hidden" id="nodeReference" name="nodeReference" value="<?php print $nodeReference ?>" />
				</div>
				<label for="sortOrder">Sort order</label>
				<input type="text" id="sortOrder" name="sortOrder" value="<?php print $sortOrder ?>" />
				<label for="icon">Icon</label>
				<input type="text" id="icon" name="icon" value="<?php print $icon ?>" />
				<label for="code">Code</label>
				<textarea id="code" name="code"><?php print htmlspecialchars($code) ?></textarea>
				<label for="listCode">List code</label>
				<textarea id="listCode" name="listCode"><?php print htmlspecialchars($listCode) ?></textarea>
			</div>
            <label for="name">Name</label>
			<input type="text" id="name" name="name" value="<?php print $name ?>" />
			<label for="name">State</label>
			<select id="state" name="state">
				<option value="working">Working</option>
				<option value="active">Active</option>
			</select>
			<div class="customFields">
				<?php
					$fields = getEntities($type);

					while (list ($thisId, $thisName, $thisState, $thisIcon, $thisType, $thisParentId, $thisPublishDate, $thisSortOrder, $thisNodeReference, $thisData) = mysql_fetch_row ($fields))
					{
						?>
							<div>
								<label for="<?php print $thisName ?>"><?php print $thisName ?></label>
								<?php if ($thisType == 167) { ?>
									<input type="text" id="<?php print $thisName ?>" name="data_<?php print $thisName ?>" value="<?php print getValueFromString($thisName, $data) ?>">
								<?php } else if ($thisType == 166) { ?>
									<textarea id="<?php print $thisName ?>" name="data_<?php print $thisName ?>"><?php print getValueFromString($thisName, $data) ?></textarea>
								<?php } else if ($thisType == 174) { ?>
									<select id="<?php print $thisName ?>" name="data_<?php print $thisName ?>" data-value="<?php print getValueFromString($thisName, $data) ?>">
										<?php
											$options = getEntities($thisId);

											while (list ($optionId, $optionName) = mysql_fetch_row ($options))
											{
												?>
													<option value="<?php print $optionId ?>"><?php print $optionName ?></option>
												<?php
											}
										?>
									</select>
								<?php } else if ($thisType == 172) { ?>
									<div class="linkField"><?php print getEntityName(getValueFromString($thisName, $data)) ?></div>
									<div class="linkButton"></div>
									<input type="hidden" id="<?php print $thisName ?>" name="data_<?php print $thisName ?>" value="<?php print getValueFromString($thisName, $data) ?>" />
								<?php } else {print "Unknown type: " . $thisType; } ?>
							</div>
						<?php
					}
				?>
			</div>
			<div class="saveAndCancelButtons">
				<div class="center">
					<input type="button" class="cancelButton" value="Cancel"/>
					<input type="submit" class="saveButton" value="Save"/>
				</div>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
	$(document).ready(function () {
		$(".cancelButton").click(function (e) {
			$("#editFormContainer").slideUp(200);
		});

		$(".toggleSystemProperties").click(function() {
			$(".systemProperties").slideToggle(200);
		});

		$("select").each(function () {
			if($(this).attr("data-value") != "") {
				$(this).val($(this).attr("data-value"));
			} else {
				console.log("nemas valuadas");
				$(this).selectedIndex = 1;
			}
		});

		$("#state").val("<?php print $state ?>");
	});
</script>