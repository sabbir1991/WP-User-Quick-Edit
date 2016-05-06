<script type="text/html" id="tmpl-user-inline-edit-template">
    <?php
        $wp_user_list_table = _get_list_table('WP_Users_List_Table');
        $column_count = $wp_user_list_table->get_column_count();
    ?>
    <tr id="edit-{{data.ID}}" class="inline-edit-row inline-edit-row-user inline-edit-user quick-edit-row quick-edit-row-user inline-edit-user">
        <td colspan="<?php echo $column_count; ?>" class="colspanchange">
            <fieldset class="inline-edit-col-left">
                <legend class="inline-edit-legend">Quick Edit</legend>

                <div class="inline-edit-col">
                    <label class="">
                        <span class="title">Email</span>
                        <span class="input-text-wrap">
                            <input type="text" name="email" class="ptitle" value="{{ data.user_email }}"></span>
                    </label>

                    <label class="">
                        <span class="title">First Name</span>
                        <span class="input-text-wrap"><input type="text" name="first_name" class="ptitle" value="{{ data.first_name }}"></span>
                    </label>

                    <label class="">
                        <span class="title">Last Name</span>
                        <span class="input-text-wrap"><input type="text" name="last_name" class="ptitle" value="{{ data.last_name }}"></span>
                    </label>

                    <label class="">
                        <span class="title">Nickname</span>
                        <span class="input-text-wrap"><input type="text" id="nickname" name="nickname" class="ptitle" value="{{ data.nickname }}"></span>
                    </label>

                    <label class="">
                        <span class="title">Role</span>
                        <span class="input-text-wrap">
                            <select name="role" id="role" class="select-field" data-selected="{{ data.role }}">
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

            <fieldset class="inline-edit-col-center">
                <div class="inline-edit-col">
                    <label class="inline-edit-tags user-display-name-lable">
                        <span class="title">Display Name publicly as</span>
                        <select name="display_name" id="display_name">
                            <# _.each( data.display_name_options.split(','), function(val, i) { #>
                                <# var display_name_selected = ( val == data.display_name ) ? 'selected' : ''; #>
                                <option {{ display_name_selected }}>{{ val }}</option>
                            <# }) #>
                        </select>
                    </label>

                    <label class="">
                        <span class="title">Website</span>
                        <span class="input-text-wrap"><input type="url" id="url" name="url" class="ptitle" value="{{ data.user_url }}"></span>
                    </label>

                    <?php do_action( 'wp_user_quick_edit_form_center' ); ?>
                </div>
            </fieldset>

            <fieldset class="inline-edit-col-right">
                <label class="inline-edit-tags">
                    <span class="title">Biographical Info</span>
                    <textarea cols="22" rows="1" name="description" class="" autocomplete="off">{{{ data.description }}}</textarea>
                </label>

                <div class="inline-edit-group wp-clearfix">
                    <label class="alignleft">
                        <input type="checkbox" name="rich_editing" value="false" data-checked="{{data.rich_editing}}">
                        <span class="checkbox-title">Disable the visual editor when writing</span>
                    </label>

                    <label class="alignleft">
                        <input type="checkbox" name="comment_shortcuts" value="true" data-checked="{{ data.comment_shortcuts }}">
                        <span class="checkbox-title">Enable keyboard shortcuts for comment moderation</span>
                    </label>

                    <label class="alignleft">
                        <input type="checkbox" name="admin_bar_front" value="1" data-checked="{{ data.admin_bar_front }}">
                        <span class="checkbox-title">Show Toolbar when viewing site</span>
                    </label>
                </div>

                <?php do_action( 'wp_user_quick_edit_form_right' ); ?>

            </fieldset>

            <?php do_action( 'wp_user_quick_edit_custom_fields' ); ?>

            <p class="submit inline-edit-save">
                <?php do_action( 'wp_user_quick_edit_form_submit' ); ?>
                <button type="button" class="button-secondary cancel alignleft">Cancel</button>
                <button type="button" class="button-primary save alignright">Update</button>
                <input type="hidden" name="user_id" value="{{ data.ID }}">
                <span class="spinner"></span>
                <span class="error" style="display:none"></span>
                <br class="clear">
            </p>

        </td>
    </tr>
</script>