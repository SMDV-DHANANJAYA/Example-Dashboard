<input type="submit" name="add_user" value="@if(isset($name)) Send @else Save @endif" class="btn btn-sm btn-outline-primary mr-1">
<input type="reset" value="Rest" class="btn btn-sm btn-outline-danger" @if(isset($reset)) onclick="reset_form()" @endif>
