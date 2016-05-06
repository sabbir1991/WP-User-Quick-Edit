<script type="text/html" id="tmpl-user-inline-edit-template">
    <tr id="user-inline-edit" class="inline-edit-row inline-edit-row-user inline-edit-user quick-edit-row quick-edit-row-user inline-edit-user">
        <td colspan="6" class="colspanchange">
            <fieldset class="inline-edit-col-left">
                <legend class="inline-edit-legend">Quick Edit</legend>
                <label class="">
                    <span class="title">Email</span>
                    <span class="input-text-wrap">
                        <input type="text" name="user_email" class="ptitle" value="{{ data.user_email }}"></span>
                </label>

                <label class="">
                    <span class="title">First Name</span>
                    <span class="input-text-wrap"><input type="text" name="first_name" class="ptitle" value="{{ data.first_name }}"></span>
                </label>

                <label class="">
                    <span class="title">Last Name</span>
                    <span class="input-text-wrap"><input type="text" name="last_name" class="ptitle" value="{{ data.last_name }}"></span>
                </label>
            </fieldset>

            <fieldset class="inline-edit-col-center inline-edit-categories">
                <legend class="inline-edit-legend">Additional Info</legend>

                <label class="">
                    <span class="title">Email</span>
                    <span class="input-text-wrap">
                        <select name="user_role" id="user_rle">
                            <option value="administrator">Administrator</option>
                            <option value="editor">Editor</option>
                            <option value="subscriber">Subscriber</option>
                        </select>
                    </span>
                </label>

            </fieldset>
        </td>
    </tr>
</script>