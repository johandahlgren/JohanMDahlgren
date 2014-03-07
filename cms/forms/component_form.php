<label for="componentName">Component</label>
<select id="componentName" name="data_componentName" data-value="<?php print getValueFromString("componentName", $data) ?>">
	<option value="renderer">Content renderer</option>
	<option value="bookList">Book list</option>
	<option value="test">Test component</option>
</select>
<label for="description">Description</label>
<input type="text" id="description" name="data_description" value="<?php print getValueFromString("description", $data) ?>" /><br/>	