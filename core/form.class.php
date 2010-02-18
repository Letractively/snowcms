<?php
#########################################################################
#                             SnowCMS v2.0                              #
#                          By the SnowCMS Team                          #
#                            www.snowcms.com                            #
#                  Released under the GNU GPL v3 License                #
#                     www.gnu.org/licenses/gpl-3.0.txt                  #
#########################################################################
#                                                                       #
# SnowCMS originally pawned by soren121 started some time in early 2008 #
#                                                                       #
#########################################################################
#                                                                       #
#                SnowCMS v2.0 began in November 2009                    #
#                                                                       #
#########################################################################
#                     File version: SnowCMS 2.0                         #
#########################################################################

if(!defined('IN_SNOW'))
  die;

/*
  Class: Form

  This is a very useful tool which aids in the creation, and saving, of
  form information. It is recommended that you use this when creating,
  well, any type of form to allow plugins the ability to add, remove and
  modify the form.
*/
class Form
{
  # Variable: forms
  # Holds the registered form information.
  private $forms;

  /*
    Method: __construct
  */
  public function __construct()
  {
    $this->forms = array();
  }

  /*
    Method: add

    Registers a new form.

    Parameters:
      string $form_name - The name of the form to create.
      array $options - An array of options containing information about the form.

    Returns:
      bool - Returns true on success, false on failure.

    Note:
      The following $options indices are allowed:
        callback - The callback which is passed all the form information.
        action - The URL of where to submit the form.
        method - Either POST or GET.
        submit - The text on the submit button.

      Once the form is processed using <Form::process> (might I add, successfully,
      so no errors from the data the user has submitted), an array containing the
      sanitized and/or handled data will be passed to the callback (array(column => value)).
      Also note that your callback is expected to return some value, other than
      false, unless something went wrong as well. You could return something such
      as an array of information, or just true :) But also, there is a second parameter
      which is a reference parameter to an array, to which you can add more errors to
      if there are any more errors which occurred while processing the form data.
  */
  public function add($form_name, $options)
  {
    global $api;

    # Form already registered by this name..? Is it not callable?
    if($this->form_registered($form_name) || !is_callable($options['callback']))
      return false;

    # We will use the edit method to add your options ;D
    $this->forms[$form_name] = array(
                                 'callback' => null,
                                 'action' => null,
                                 'method' => null,
                                 'submit' => l('Submit'),
                                 'fields' => array(),
                                 'errors' => array(),
                                 'hooked' => false,
                               );

    # Just some options set incase you don't.
    $default_options = array(
                        'callback' => null,
                        'action' => null,
                        'method' => 'post',
                        'submit' => l('Submit'),
                       );

    $options = array_merge($default_options, is_array($options) ? $options : array());

    # Told you! :D
    if(!$this->edit($form_name, $options))
      # Hmm, it didn't work... Maybe you ought to fix that? :P
      return false;

    $token = $api->load_class('Tokens');

    # Only recreate the token if it does not exist.
    if(!$token->exists($form_name))
      $token->add($form_name);

    # Our first field, a token!
    $this->add_field($form_name, 'form_token', array(
                                                 'type' => 'hidden',
                                                 'value' => $token->token($form_name),
                                                 'function' => create_function('$value, $form_name, &$error', '
                                                   global $api;

                                                   $token = $api->load_class(\'Tokens\');

                                                   if($token->is_valid($form_name, $value))
                                                    return true;
                                                  else
                                                  {
                                                    $error = l(\'Your security key is invalid. Please resubmit the form.\');
                                                    return false;
                                                  }'),
                                                 'save' => false,
                                               ));
    return true;
  }

  /*
    Method: remove

    Removes the specified form.

    Parameters:
      string $form_name - The name of the form.

    Returns:
      bool - Returns true on success, false on failure.
  */
  public function remove($form_name)
  {
    if(!$this->form_registered($form_name))
      return false;

    unset($this->forms[$form_name]);
    return true;
  }

  /*
    Method: form_registered

    Checks to see if the specified form name is in use.

    Parameters:
      string $form_name - The name of the form.

    Returns:
      bool - Returns true if the form is registered, false if not.
  */
  public function form_registered($form_name)
  {
    return isset($this->forms[$form_name]);
  }

  /*
    Method: edit

    Allows you to edit the specified form information.

    Parameters:
      string $form_name - The name of the form.
      array $options - An array containing the new values.

    Returns:
      bool - Returns true on success, false on failure.

    Note:
      Allowed options:
        callback - The callback of the form.
        action - The URL to submit the form to.
        method - Either GET or POST.
  */
  public function edit($form_name, $options)
  {
    # Can't edit something that doesn't exist, now can we?
    if(!$this->form_registered($form_name))
      return false;

    # Editing the callback? Make sure it is callable.
    if(isset($options['callback']) && is_callable($options['callback']))
      $this->forms[$form_name]['callback'] = $options['callback'];
    elseif(isset($options['callback']))
      return false;

    # The action?
    if(isset($options['action']))
      $this->forms[$form_name]['action'] = $options['action'];

    # How about the method of transporation? ;) Only get or post.
    if(isset($options['method']) && in_array(strtolower($options['method']), array('get', 'post')))
      $this->forms[$form_name]['method'] = strtolower($options['method']);
    elseif(isset($options['method']))
      return false;

    # The text on the submit button, perhaps?
    if(isset($options['submit']))
      $this->forms[$form_name]['submit'] = $options['submit'];

    # If nothing caused false to be returned elsewhere, it worked!
    return true;
  }

  /*
    Method: add_field

    Adds a field to the specified form.

    Parameters:
      string $form_name - The name of the form.
      string $name - The name of the input/textarea.
      array $options - Options about how the input should be formed and handled.
                       Look at the note below for more information.

    Returns:
      bool - Returns true on success, false on failure.

    Note:
      Here are acceptable indices, and their expected values, for the $options parameter:
        column - The name of the column in the database for which this value will be used.
                 If nothing is supplied, the name of the input/textarea will be used.

        type - The accepted types of a field are as follows (required):
                 - hidden - A hidden input, ooooo! I wonder what you want to hide?

                 - int - An integer value.

                 - double - A double value.

                 - string - A string value (input type="text")

                 - string-html - Same as above, but HTML tags are not sanitized with htmlchars.

                 - text - A string value, however, it is a textarea.

                 - text-html - Same as above, but HTML tags are not sanitized with htmlchars.

                 - password - A password field.

                 - checkbox - A checkbox field.

                 - select - An options list (<select>), you are then supposed to supply
                            the options values.

                 - select-multi - An options list, but multiple values can be selected.

                 - function - This means the system will do no checking by itself, and
                              all will be handled by the supplied function callback.

                 - custom(-{type}) - Allows you to set a custom HTML value for the value index,
                                     you are expected to form the input/textarea tag yourself,
                                     however, you have to append -{TYPE} to the end of custom
                                     which tells the system what kind of value to expect. If
                                     that is not appended, you are required to supply a function
                                     which handles the data before it is entered into the database.
                                     Also note that the value will be handled as a callback.

                 - full(-{type}) - Much like custom(-{type}), however, this one gives you full control
                                   of a <td> tag which has an attribute of colspan="2".

        label - The label of the input (the text previous to the input/textarea), be sure
                to run it through the l function! If nothing is supplied the column name
                is used instead.

        subtext - A description which is put below the label. (Optional)

        popup - Supply true if there is a popup (which should contain a more comprehensive
                set of information) that can be displayed. Apply a filter to help_{$column}.
                Defaults to false.

        length - An array of length restrictions. Ex: array('min' => 10, 'max' => 100). If
                 that was supplied the string could be a minimum length of 10 and a maximum
                 length of 100, if the string was not, an error would be shown. However, if
                 its type was an int/double the value could be, at minimum of 10 and a max
                 of 100, otherwise an error would be thrown. If no minimum is supplied, no
                 minimum will be expected (0), if no maximum is supplied, the length will
                 be unlimited. This option can only be used with the types: int, double,
                 and string.

        truncate - This goes along the length index. If you set this to true, and a max
                   length is specified, then the value will be truncated according to the
                   maximum length. If it is a string (value: Hello), and a max of 2, the
                   value will be truncated at a length of 2 (He). However, if it is an
                   int/double (value: 50) and the maximuym is 25, the value will be 25.
                   Defaults to false.

        options - An array of options, such as: array('Option 1', 'Option 2', 'Option 3')
                  or array('yes' => 'Yes', 'no' => 'No'), the index being the value in the
                  database, and the value being the value displayed in the options list.

        function - A function callback, which is required if the type if function but optional
                   if it is anything else. This function will be called before (if any) any
                   system checking is done. Three parameter will be supplied, which is the value
                   (make sure you make it a reference parameter that way you can modify it), the
                   forms name and lastly, a reference parameter which contains the error, if any.
                   Your function is expected to return true if the value is okay (or that you
                   made it okay) and false if it is invalid. If you return nothing, then it
                   will see it as false.

        value - The current value of the field. For numeric and string fields, the value is
                used as is, however, with checkboxes: 0 unchecked, 1 checked, select: selected="selected"
                put on the selected option, select-multi: same as previous one, except possibly
                on multiple options. Defaults to nothing. Please note that the value is automatically
                encoded by htmlchars.

        disabled - true if the field is disabled (value cannot be changed by the user) and false
                   if it is enabled. Defaults to false.

        show - Set this to false if you do not want this field to be displayed, true if you want
               it to be shown. Defaults to true.

        save - Whether or not to include the field when being passed to the saving function.
               Defaults to true. This is useful for password verification, but also this is
               used internally for XSRF protection (fyi ;)).

        rows - The number of rows in the textarea. Only valid for text, text-html and select-multi.
               When set for select-multi it sets the size attribute.

        cols - The number of columns in the textarea. Only valid for text and text-html.

      Just so you know, this is how each type will be saved to the database:
        hidden - As is (See string).
        int - As is.
        double - As is.
        string - As is with HTML tags encoded.
        string-html - As is.
        text - As is with HTML tags encoded.
        text-html - As is.
        password - As is.
        checkbox - 0 for unchecked, 1 for checked.
        select - The index of the option value. For example:
                   options = array('This setting', 'Another setting')
                 If "Another setting" was chosen, 1 would be stored in the database
                 as that is its index, however, you can do 'another' => 'Another setting'
                 and "another" would be stored in the database.
        select-multi - As is above, except each selected option will be comma delimited.
  */
  public function add_field($form_name, $name, $options = array())
  {
    # The form not registered? Is this field name already specified?
    if(!$this->form_registered($form_name) || $this->field_registered($form_name, $name))
      return false;

    # Validate that puppy!
    $field = $this->validate_field($name, $options);

    # Did you do something you shouldn't have? Tisk tisk!
    if($field === false)
      return false;

    # Add it.
    $this->forms[$form_name]['fields'][$name] = $field;

    return true;
  }

  /*
    Method: validate_field

    Validates the supplied field information, this is a helper method
    for <Form::add_field>, and also used for <Form::edit_field> as well.

    Parameters:
      string $name - The name of the field.
      array $options - An array of options.

    Returns:
      mixed - Returns an array on success, and false on failure.
  */
  private function validate_field($name, $options)
  {
    # Holds all of our stoof :)
    $field = array();

    # A column specified? Use that, otherwise, the supplied field name.
    $field['column'] = !empty($options['column']) ? $options['column'] : $name;

    # Here is an array containing all the recognized types.
    $allowed_types = array('hidden', 'int', 'double', 'string', 'string-html', 'text', 'text-html', 'password', 'checkbox', 'select', 'select-multi', 'function', 'custom');

    if(empty($options['type']))
      return false;

    # Before we validate the supplied, it might be custom!
    $field['type'] = '';
    $options['type'] = strtolower($options['type']);
    $field['is_full'] = $options['type'] == 'full' || substr($options['type'], 0, 5) == 'full-';
    $field['is_custom'] = $options['type'] == 'custom' || substr($options['type'], 0, 7) == 'custom-' || $field['is_full'];

    if($field['is_custom'] && !$field['is_full'] && strlen($field['type']) > 7)
      $options['type'] = substr($options['type'], 7, strlen($options['type']) - 7);

    if($field['is_full'] && strlen($field['type']) > 5)
      $options['type'] = subtr($options['type'], 5, strlen($options['type']) - 5);

    # So, is it valid?
    if(in_array($options['type'], $allowed_types))
      $field['type'] = $options['type'];
    elseif(empty($field['is_custom']) && $field['type'] != 'full')
      return false;

    # Label isn't required, but, c'mon, its a good idea ;)
    $field['label'] = isset($options['label']) ? $options['label'] : $field['column'];

    # Same goes for subtext.
    $field['subtext'] = isset($options['subtext']) ? $options['subtext'] : '';

    # How about a popup? More information never hurt anyone. I think.
    $field['popup'] = !empty($options['popup']);

    # A length isn't required either, so let's see.
    $field['length'] = array(
                         'min' => null,
                         'max' =>null,
                       );

    if(!empty($options['length']['min']) && (string)$options['length']['min'] == (string)(int)$options['length']['min'])
      $field['length']['min'] = (int)$options['length']['min'];

    if(!empty($options['length']['max']) && (string)$options['length']['max'] == (string)(int)$options['length']['max'])
      $field['length']['max'] = (int)$options['length']['max'];

    # To truncate, or to not truncate, that is the question!
    $field['truncate'] = !empty($options['truncate']);

    # We only need options if your fields type is select or select-multi.
    if($field['type'] == 'select' || $field['type'] == 'select-multi')
    {
      # Nothing supplied?!
      if(!isset($options['options']) || !is_array($options['options']))
        return false;

      $field['options'] = $options['options'];

      if(count($field['options']))
        foreach($field['options'] as $key => $value)
          $field['options'][$key] = htmlchars($value);
    }

    # A function, perhaps?
    $field['function'] = isset($options['function']) && is_callable($options['function']) ? $options['function'] : null;

    # Maybe a value? (Only encode the value if it isn't custom, as they need HTML ;))
    $field['value'] = isset($options['value']) ? ($field['is_custom'] && is_callable($options['value']) ? $options['value'] : (is_array($options['value']) ? $options['value'] : htmlchars($options['value']))) : '';

    # Disabled?
    $field['disabled'] = !empty($options['disabled']);

    # Should we show/handle this field at all?
    $field['show'] = isset($options['show']) ? !empty($options['show']) : true;

    # Pass it to the callback?
    $field['save'] = isset($options['save']) ? !empty($options['save']) : true;

    # Rows, columns?
    $field['rows'] = isset($options['rows']) && (string)$options['rows'] == (string)(int)$options['rows'] ? (int)$options['rows'] : 0;
    $field['cols'] = isset($options['cols']) && (string)$options['cols'] == (string)(int)$options['cols'] ? (int)$options['cols'] : 0;

    # Woo! We are done!
    return $field;
  }

  /*
    Method: remove_field

    Removes the field from the specified form.

    Parameters:
      string $form_name - The name of the form.
      string $name - The name of the field.

    Returns:
      bool - Returns true on success, false on failure.
  */
  public function remove_field($form_name, $name)
  {
    if(!$this->field_registered($form_name, $name))
      return false;

    unset($this->forms[$form_name]['fields'][$name]);
    return true;
  }

  /*
    Method: field_registered

    Checks to see if the supplied field is registered on the
    specified form.

    Parameters:
      string $form_name - The name of the form.
      string $name - The name of the field.
  */
  public function field_registered($form_name, $name)
  {
    return isset($this->forms[$form_name]['fields'][$name]);
  }

  /*
    Method: edit_field

    Allows you to edit the field on the specified form.

    Parameters:
      string $form_name - The name of the form.
      string $name - The name of the field.
      array $options - An array containing all the options
                       you want to be added/changed in the fields
                       current setup.

    Returns:
      bool - Returns true on success, false on failure.
  */
  public function edit_field($form_name, $name, $options)
  {
    # The field not registered? Then you certainly can't edit what isn't there!
    if(!$this->field_registered($form_name, $name))
      return false;

    # Get the current options, merge the new ones and validate them. If validation
    # fails, we just won't actually update them :P
    $field = $this->validate_field($name, array_merge($this->forms[$form_name]['fields'][$name], $options));

    # Did YOU fail? :P
    if($field === false)
      return false;

    # So it worked, sweet.
    $this->forms[$form_name]['fields'][$name] = $field;

    return true;
  }

  /*
    Method: show

    Shows the specified form in HTML form.

    Parameters:
      string $form_name - The name of the form to display.

    Returns:
      void - Nothing is returned by this method.
  */
  public function show($form_name)
  {
    global $api;

    if(!$this->form_registered($form_name))
    {
      echo l('The form "%s" does not exist.', htmlchars($form_name));
      return;
    }

    # Before we display the form, let's let yalls have at it.
    # So you can add, remove and edit fields and such :)
    # But of course only run the hook here if <Form::process> has
    # yet to be called ;)
    if(empty($this->forms[$form_name]['hooked']))
    {
      $api->run_hook($form_name);
      $this->forms[$form_name]['hooked'] = true;
    }

    # If you want to display the forms in your own special way, just hook into here :)
    $handled = null;
    $api->run_hook('form_show', array($form_name, $this->forms[$form_name], &$handled));

    if(empty($handled))
    {
      echo '
      <form action="', $this->forms[$form_name]['action'], '" method="', $this->forms[$form_name]['method'], '" class="', $form_name, '" id="', $form_name, '">
        <fieldset>
          <table>
            <tr>
              <td colspan="2" id="', $form_name, '_errors">';

      # Any errors? Those needs displayin'!
      if(count($this->forms[$form_name]['errors']) > 0)
      {
        echo '
                <div class="errors">';

        foreach($this->forms[$form_name]['errors'] as $error)
          echo '
                  <p>', $error, '</p>';

        echo '
                </div>';
      }

        echo '
              </td>
            </tr>';

      # Show the fields, you know, the things you enter stuff into.
      if(count($this->forms[$form_name]['fields']) > 0)
        foreach($this->forms[$form_name]['fields'] as $name => $field)
          # Make this simple, show it!
          $this->show_field($form_name, $name, $field);

      echo '
            <tr id="', $form_name, '_submit">
              <td class="buttons" colspan="2"><input type="submit" name="', $form_name, '" value="', $this->forms[$form_name]['submit'], '" /></td>
            </tr>
          </table>
        </fieldset>
      </form>';
    }
  }

  /*
    Method: show_field

    Outputs a field according to the parameters supplied.

    Parameters:
      string $form_name - The name of the form the field is within.
      string $name - The name of the field.
      array $field - All the fields options.

    Returns:
      void - Nothing is returned by this method.
  */
  private function show_field($form_name, $name, $field)
  {
    global $api;

    # Do you want to do this?
    $handled = null;
    $api->run_hook('form_show_field', array(&$handled, $form_name, $name, $field));

    # Did someone else not handle it? Should it even be shown?
    if(empty($handled) && !empty($field['show']))
    {
      echo '
            <tr id="', $form_name, '_', $name, '">';

      # Is the field hidden? Then showing something isn't very hidden, now is it? I didn't think so.
      if($field['type'] != 'hidden' && $field['type'] != 'full')
        echo '
              <td id="', $form_name, '_', $name, '_left" class="td_left"><p class="label">', $field['label'], '</p>', !empty($field['subtext']) ? '<p class="subtext">'. $field['subtext']. '</p>' : '', '</td>';

      # Now here is the fun part! Actually displaying the fields.
      if(empty($field['is_custom']) && in_array($field['type'], array('int', 'double', 'string', 'string-html', 'password')))
      {
        echo '
              <td id="', $form_name, '_', $name, '_right" class="td_right"><input id="', $form_name, '_', $name, '_input" type="', ($field['type'] == 'password' ? 'password' : 'text'), '" name="', $name, '" value="', $field['value'], '"', ($field['length']['max'] > 0 ? ' maxlength="'. $field['length']['max']. '"' : ''), (!empty($field['disabled']) ? ' disabled="disabled"' : ''), ' /></td>';
      }
      elseif(empty($field['is_custom']) && in_array($field['type'], array('text', 'text-html')))
      {
        echo '
              <td id="', $form_name, '_', $name, '_right" class="td_right"><textarea id="', $form_name, '_', $name, '_input" name="', $name, '"', ($field['length']['max'] > 0 ? ' onkeyup="s.truncate(this, '. $field['length']['max']. ');"' : ''), ($field['rows'] > 0 ? ' rows="'. $field['rows']. '"' : ''), ($field['cols'] > 0 ? ' cols="'. $field['cols']. '"' : ''), ($field['length']['max'] > 0 ? ' maxlength="'. $field['length']['max']. '"' : ''), (!empty($field['disabled']) ? ' disabled="disabled"' : ''), '>', $field['value'], '</textarea></td>';
      }
      elseif(empty($field['is_custom']) && in_array($field['type'], array('select', 'select-multi')))
      {
        echo '
              <td id="', $form_name, '_', $name, '_right" class="td_right">
                <select id="', $form_name, '_', $name, '_input" name="', $name, '"', ($field['type'] == 'select-multi' ? ' multiple="multiple"' : ''), ($field['type'] == 'select-multi' && $field['rows'] > 0 ? ' size="'. $field['rows']. '"' : ''), ($field['length']['max'] > 0 ? ' maxlength="'. $field['length']['max']. '"' : ''), (!empty($field['disabled']) ? ' disabled="disabled"' : ''), '>';

                if(count($field['options']))
                  foreach($field['options'] as $key => $value)
                    echo '
                  <option value="', $key, '"', ((!is_array($field['value']) && $field['value'] == $value) || (is_array($field['value']) && in_array($value, $field['value'])) ? ' selected="selected"' : ''), '>', $value, '</option>';

        echo '
                </select>
              </td>';
      }
      elseif(empty($field['is_custom']) && $field['type'] == 'checkbox')
      {
        echo '
              <td id="', $form_name, '_', $name, '_right" class="td_right"><input id="', $form_name, '_', $name, '_input" type="checkbox" name="', $name, '" value="1"', (!empty($field['value']) ? ' checked="checked"' : ''), (!empty($field['disabled']) ? ' disabled="disabled"' : ''), ' /></td>';
      }
      elseif(empty($field['is_custom']) && $field['type'] == 'hidden')
      {
        echo '
              <td id="', $form_name, '_', $name, '_right" class="hidden" colspan="2"><input id="', $form_name, '_', $name, '_input" type="hidden" name="', $name, '" value="', $field['value'], '"', (!empty($field['disabled']) ? ' disabled="disabled"' : ''), ' /></td>';
      }
      elseif(empty($field['is_full']))
      {
        echo '
              <td id="', $form_name, '_', $name, '_right" class="td_right">', (is_callable($field['value']) ? $field['value']() : $field['value']), '</td>';
      }
      else
      {
        echo '
              <td id="', $form_name, '_', $name, '_right" class="full" colspan="2">', (is_callable($field['value']) ? $field['value']() : $field['value']), '</td>';
      }

      echo '
            </tr>';
    }
  }

  /*
    Method: process

    Actually processes and handles the submitting of the created forms.
    This method handles the sanitization and error handling of the data
    submitted by the user.

    Parameters:
      string $form_name - The name of the form.

    Returns:
      mixed - Returns false on failure, however, on success whatever the
              forms callback is set to return will be returned on success.
  */
  public function process($form_name)
  {
    global $api, $func;

    if(!$this->form_registered($form_name))
    {
      echo l('The form "%s" does not exist.', htmlchars($form_name));
      return;
    }

    # Run the hook, so you can make any modifications and what not to the form.
    # Note: This is the same exact hook in <Form::show> it is just that when the
    #       form is submitted, this method will be called before showing, so this
    #       just needs to be done ;)
    if(empty($this->forms[$form_name]['hooked']))
    {
      $api->run_hook($form_name);
      $this->forms[$form_name]['hooked'] = true;
    }

    # Do you want the fun of handling this form? You be my guest!
    $errors = null;
    $handled = null;
    $api->run_hook('form_process', array(&$handled, $form_name, $this->forms[$form_name], &$errors));

    if($handled !== null)
    {
      if(is_array($errors))
        $this->forms[$form_name]['errors'] = $errors;

      return !empty($handled);
    }
    else
    {
      # If the form wasn't actually submitted, then we couldn't process it right...
      if(empty($_POST[$form_name]) || count($_POST) == 0 || count($this->forms[$form_name]['fields']) == 0)
        return false;

      # Reset the errors array, just incase.
      $this->forms[$form_name]['errors'] = array();

      # We will need the validation class, that is for sure!
      $validation = $api->load_class('Validation');

      # Now this is the super fun part, processing everything!!!
      $processed = array();
      foreach($this->forms[$form_name]['fields'] as $name => $field)
      {
        # Field disabled? Not supposed to be shown? Then you can't supply any information about this.
        if(!empty($field['disabled']) || empty($field['show']))
          continue;

        # Is the POST field not even set? Well, we will set it then. To empty! ;)
        if(empty($_POST[$name]))
          $_POST[$name] = '';

        # Any function to run, perhaps? Do so now.
        if(!empty($field['function']) && is_callable($field['function']))
        {
          $error = '';
          if(!$field['function']($_POST[$name], $form_name, $error))
          {
            # So something went wrong, did it?
            $this->forms[$form_name]['errors'][] = $error;

            # No need to continue, you said something was wrong!
            continue;
          }
        }

        # No passing this field to the forms callback? Then we're done!
        if(empty($field['save']))
          continue;

        # Now it is time to check the data types of the submitted form data, woo!!!
        # So, is it a string(-html), text(-html), password or a hidden field?
        if(in_array($field['type'], array('string', 'string-html', 'text', 'text-html', 'password', 'hidden')))
        {
          # Set as a string field, in reality, anything can be a string.
          if(!$validation->data($_POST[$name], 'string'))
          {
            $this->forms[$form_name]['errors'][] = l('The "%s" field must be a string.', htmlchars($this->forms[$form_name]['fields'][$name]['label']));
            continue;
          }

          # But does it need encoding?!
          if(in_array($field['type'], array('string', 'text', 'password', 'hidden')))
            $_POST[$name] = htmlchars($_POST[$name]);
        }
        # How about an integer or double?
        elseif($field['type'] == 'int' || $field['type'] == 'double')
        {
          # Temporarily type-cast the value to an integer, if it isn't the same, it isn't one.
          if(!$validation->data($_POST[$name], $field['type']))
          {
            $this->forms[$form_name]['errors'][] = l('The "%s" field must be an '. ($field['type'] == 'int' ? 'integer' : 'number'). '.', htmlchars($this->forms[$form_name]['fields'][$name]['label']));
            continue;
          }
        }
        # Could it be a checkbox?
        elseif($field['type'] == 'checkbox')
        {
          # Simple :)
          $_POST[$name] = !empty($_POST[$name]) ? 1 : 0;
        }
        # Select of some sort?
        elseif($field['type'] == 'select' || $field['type'] == 'select-multi')
        {
          $is_multi = $field['type'] == 'select-multi';

          $selected = array();
          $options = array_keys($field['options']);

          # Now to see which ones you selected, if any.
          if(is_array($_POST[$name]) && count($_POST[$name]) > 0)
          {
            foreach($_POST[$name] as $option_key)
            {
              # Is it even a valid option?
              if(in_array($option_key, $options))
              {
                $selected[] = $option_key;

                if(isset($field['length']['max']) && count($selected) >= $field['length']['max'])
                  break;
              }
            }
          }
          elseif(!$is_multi)
            $selected[] = $_POST[$name];

          # Join them all together, like one happy family! Of one ;D
          $_POST[$name] = implode(',', $selected);
        }

        # Any length restrictions set?
        if((isset($field['length']['min']) || isset($field['length']['max'])) && ($field['type'] == 'int' || $field['type'] == 'double'))
        {
          if(isset($field['length']['min']) && $_POST[$name] < $field['length']['min'])
          {
            $this->forms[$form_name]['errors'][] = l('The field "%s" must be at least %f.', $this->forms[$form_name]['fields'][$name]['label'], $field['length']['min']);
            continue;
          }
          elseif(isset($field['length']['max']) && ($truncate = ($_POST[$name] > $field['length']['max'])) && empty($field['truncate']))
          {
            $this->forms[$form_name]['errors'][] = l('The field "%s" must be smaller than %f.', $this->forms[$form_name]['fields'][$name]['label'], $field['length']['max']);
            continue;
          }

          if(!empty($truncate))
            $_POST[$name] = $field['type'] == 'int' ? (int)$field['length']['max'] : (double)$field['length']['min'];
        }
        elseif((isset($field['length']['min']) || isset($field['length']['max'])) && in_array($field['type'], array('string', 'string-html', 'text', 'text-html', 'password', 'hidden')))
        {
          if(isset($field['length']['min']) && $func['strlen']($_POST[$name]) < $field['length']['min'])
          {
            $this->forms[$form_name]['errors'][] = l('The field "%s" must be at least %d characters long.', $this->forms[$form_name]['fields'][$name]['label'], $field['length']['min']);
            continue;
          }
          elseif(isset($field['length']['max']) && $field['length']['max'] > -1 && ($truncate = ($func['strlen']($_POST[$name]) > $field['length']['max'])) && empty($field['truncate']))
          {
            $this->forms[$form_name]['errors'][] = l('The field "%s" must be smaller than %d characters.', $this->forms[$form_name]['fields'][$name]['label'], $field['length']['max']);
            continue;
          }

          # Truncation needed/wanted?
          if(!empty($truncate))
            $_POST[$name] = $func['substr']($_POST[$name], 0, $field['length']['max']);
        }

        # If we got here, then everything is good, so add the value :)
        $processed[$field['column']] = $_POST[$name];
      }

      # No errors? Then everything is good!
      if(count($this->forms[$form_name]['errors']) == 0)
      {
        # Give the callback the processed information so they can do whatever ;)
        # And return what it returned!!!
        $errors = array();
        $success = $this->forms[$form_name]['callback']($processed, $errors);

        # Did it fail?
        if($success === false)
        {
          # Any more errors? Add them.
          if(count($errors))
            foreach($errors as $error)
              $this->forms[$form_name]['errors'][] = $error;

          return false;
        }
        else
          # We don't return just true, since the callback could return another value.
          return $success;
      }
      else
      {
        # Form processing failed!!!
        return false;
      }
    }
  }

  /*
    Method: json_process

    This is almost identical to the <Form::process> method in every way,
    except this method returns a JSON encoded string containing information
    about the submission of the form. Check the notes for more information.

    Parameters:
      string $form_name - The name of the form.

    Returns:
      string - Returns a JSON-encoded string.

    Note:
      The JSON encoded string contains an array of the error messages which
      occurred while processing the form.
  */
  public function json_process($form_name)
  {
    # Even though process does this, it echo's the data, which we don't want.
    if(!$this->form_registered($form_name))
    {
      return json_encode(array(l('The form "%s" does not exist.', htmlchars($form_name))));
    }

    # Now process the form!
    $this->process($form_name);

    # Now return the JSON encoded string containing any errors, if any, of course!
    return json_encode($this->forms[$form_name]['errors']);
  }
}
?>