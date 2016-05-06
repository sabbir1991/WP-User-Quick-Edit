<script type="text/html" id="tmpl-user-inline-edit-template">
    <tr id="edit-{{data.ID}}" class="inline-edit-row inline-edit-row-user inline-edit-user quick-edit-row quick-edit-row-user inline-edit-user">
        <td colspan="6" class="colspanchange">
            <fieldset class="inline-edit-col-left">
                <legend class="inline-edit-legend">Quick Edit</legend>

                <div class="inline-edit-col">
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
                </div>

            </fieldset>

            <fieldset class="inline-edit-col-center">
                <legend class="inline-edit-legend">Additional Info</legend>

                <div class="inline-edit-col">
                    <label class="">
                        <span class="title">Role</span>
                        <span class="input-text-wrap">
                            <select name="role" id="role" data-selected="{{ data.role }}">
                                <?php
                                    // print the full list of roles with the primary one selected.
                                    wp_dropdown_roles();
                                ?>

                                <# if ( data.role ) { #>
                                    <option value=""><?php _e('&mdash; No role for this site &mdash;'); ?> </option>
                                <# } else { #>
                                    <option value="" selected="selected"><?php _e('&mdash; No role for this site &mdash;') ?></option>
                                <# } #>
                            </select>
                        </span>
                    </label>
                </div>
            </fieldset>

            <fieldset class="inline-edit-col-right">
                <legend class="inline-edit-legend">Others</legend>
                <div class="inline-edit-col">
                    <label class="alignleft">
                        <input type="checkbox" name="checkbox" value="yes">
                        <span class="checkbox-title">Allow this user</span>
                    </label>
                </div>
            </fieldset>

            <p class="submit inline-edit-save">
                <button type="button" class="button-secondary cancel alignleft">Cancel</button>
                <button type="button" class="button-primary save alignright">Update</button>
                <span class="spinner"></span>
                <span class="error" style="display:none">This isa a error</span>
                <br class="clear">
            </p>

        </td>
    </tr>
</script>