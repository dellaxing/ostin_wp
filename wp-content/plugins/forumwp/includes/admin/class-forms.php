<?php
namespace fmwp\admin;


if ( ! defined( 'ABSPATH' ) ) exit;


if ( ! class_exists( 'fmwp\admin\Forms' ) ) {


	/**
	 * Class Forms
	 *
	 * @package fmwp\admin
	 */
	class Forms {


		/**
		 * @var bool
		 */
		var $form_data;


		/**
		 * Forms constructor.
         *
		 * @param bool $form_data
		 */
		function __construct( $form_data = false ) {
			if ( $form_data ) {
				$this->form_data = $form_data;
			}
		}


		/**
		 * Set Form Data
		 *
		 * @param $data
		 *
		 * @return $this
		 */
		function set_data( $data ) {
			$this->form_data = $data;
			return $this;
		}


		/**
		 * Render form
		 *
		 *
		 * @param bool $echo
		 * @return string
		 */
		function display( $echo = true ) {

			if ( empty( $this->form_data['fields'] ) ) {
				return '';
			}

			$hidden = '';
			$fields = '';
			foreach ( $this->form_data['fields'] as $field_data ) {
				if ( empty( $field_data['type'] ) ) {
					continue;
				}

				if ( 'hidden' == $field_data['type'] ) {
					$hidden .= $this->render_hidden( $field_data );
				} else {
					$fields .= $this->render_form_row( $field_data );
				}
			}

			ob_start();

			echo $hidden;

			if ( empty( $this->form_data['without_wrapper'] ) ) {
				$class = 'form-table fmwp-form-table ' . ( ! empty( $this->form_data['class'] ) ? $this->form_data['class'] : '' );
				$class_attr = ' class="' . $class . '" '; ?>

				<table <?php echo $class_attr ?>>
					<tbody><?php echo $fields; ?></tbody>
				</table>

			<?php } else {
				echo $fields;
			}

			if ( $echo ) {
				ob_get_flush();
				return '';
			} else {
				return ob_get_clean();
			}
		}


		/**
		 * @param array $data
		 *
		 * @return string
		 */
		function render_form_row( $data ) {

			if ( ! empty( $data['value'] ) && $data['type'] != 'email_template' ) {
				$data['value'] = wp_unslash( $data['value'] );

				/*for multi_text*/
				if ( ! is_array( $data['value'] ) && $data['type'] != 'wp_editor' ) {
					$data['value'] = esc_attr( $data['value'] );
				}
			}

			$conditional = ! empty( $data['conditional'] ) ? 'data-conditional="' . esc_attr( json_encode( $data['conditional'] ) ) . '"' : '';
			$prefix_attr = ! empty( $this->form_data['prefix_id'] ) ? ' data-prefix="' . $this->form_data['prefix_id'] . '" ' : '';

			$type_attr = ' data-field_type="' . $data['type'] . '" ';

			$html = '';

			if ( ! empty( $this->form_data['div_line'] ) ) {

				if ( strpos( $this->form_data['class'], 'fmwp-top-label' ) !== false ) {

					$html .= '<div class="form-field fmwp-forms-line" ' . $conditional . $prefix_attr . $type_attr . '>' . $this->render_field_label( $data );

					if ( method_exists( $this, 'render_' . $data['type'] ) ) {

						$html .= call_user_func( [ &$this, 'render_' . $data['type'] ], $data );

					} else {

						$html .= $this->render_field_by_hook( $data );

					}

					if ( ! empty( $data['description'] ) )
						$html .= '<p class="description">' . $data['description'] . '</p>';

					$html .= '</div>';

				} else {

					if ( ! empty( $data['without_label'] ) ) {

						$html .= '<div class="form-field fmwp-forms-line" ' . $conditional . $prefix_attr . $type_attr . '>';

						if ( method_exists( $this, 'render_' . $data['type'] ) ) {

							$html .= call_user_func( [ &$this, 'render_' . $data['type'] ], $data );

						} else {

							$html .= $this->render_field_by_hook( $data );

						}

						if ( ! empty( $data['description'] ) )
							$html .= '<p class="description">' . $data['description'] . '</p>';

						$html .= '</div>';

					} else {

						$html .= '<div class="form-field fmwp-forms-line" ' . $conditional . $prefix_attr . $type_attr . '>' . $this->render_field_label( $data );

						if ( method_exists( $this, 'render_' . $data['type'] ) ) {

							$html .= call_user_func( [ &$this, 'render_' . $data['type'] ], $data );

						} else {

							$html .= $this->render_field_by_hook( $data );

						}

						if ( ! empty( $data['description'] ) )
							$html .= '<p class="description">' . $data['description'] . '</p>';

						$html .= '</div>';

					}
				}

			} else {
				if ( strpos( $this->form_data['class'], 'fmwp-top-label' ) !== false ) {

					$html .= '<tr class="fmwp-forms-line" ' . $conditional . $prefix_attr . $type_attr . '>
					<td>' . $this->render_field_label( $data );

					if ( method_exists( $this, 'render_' . $data['type'] ) ) {

						$html .= call_user_func( [ &$this, 'render_' . $data['type'] ], $data );

					} else {

						$html .= $this->render_field_by_hook( $data );

					}

					if ( ! empty( $data['description'] ) )
						$html .= '<div class="clear"></div><p class="description">' . $data['description'] . '</p>';

					$html .= '</td></tr>';

				} else {

					if ( ! empty( $data['without_label'] ) ) {

						$html .= '<tr class="fmwp-forms-line" ' . $conditional . $prefix_attr . $type_attr . '>
						<td colspan="2">';

						if ( method_exists( $this, 'render_' . $data['type'] ) ) {

							$html .= call_user_func( [ &$this, 'render_' . $data['type'] ], $data );

						} else {

							$html .= $this->render_field_by_hook( $data );

						}

						if ( ! empty( $data['description'] ) )
							$html .= '<div class="clear"></div><p class="description">' . $data['description'] . '</p>';

						$html .= '</td></tr>';

					} else {

						$html .= '<tr class="fmwp-forms-line" ' . $conditional . $prefix_attr . $type_attr . '>
						<th>' . $this->render_field_label( $data ) . '</th>
						<td>';

						if ( method_exists( $this, 'render_' . $data['type'] ) ) {

							$html .= call_user_func( [ &$this, 'render_' . $data['type'] ], $data );

						} else {

							$html .= $this->render_field_by_hook( $data );

						}

						if ( ! empty( $data['description'] ) )
							$html .= '<div class="clear"></div><p class="description">' . $data['description'] . '</p>';

						$html .= '</td></tr>';

					}
				}
			}

			return $html;
		}


		/**
		 * @param $data
		 *
		 * @return mixed
		 */
		function render_field_by_hook( $data ) {
			return apply_filters( 'fmwp_render_field_type_' . $data['type'], '', $data, $this->form_data, $this );
		}


		/**
		 * @param $data
		 *
		 * @return bool|string
		 */
		function render_field_label( $data ) {
			if ( empty( $data['label'] ) ) {
				return '';
			}

			$id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $data['id'];
			$for_attr = ' for="' . $id . '" ';

			$label = $data['label'];
			if ( ! empty( $data['required'] ) ) {
				$label = $label . '<span class="fmwp-req" title="'. esc_attr( 'Required', 'forumwp' ).'">*</span>';
			}

			$helptip = ! empty( $data['helptip'] ) ? FMWP()->helptip( $data['helptip'], false, false ) : '';

			return "<label $for_attr>$label $helptip</label>";
		}


		/**
		 * @param $field_data
		 *
		 * @return bool|string
		 */
		function render_hidden( $field_data ) {

			if ( empty( $field_data['id'] ) ) {
				return '';
			}

			$id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $field_data['id'];
			$id_attr = ' id="' . $id . '" ';

			$class = ! empty( $field_data['class'] ) ? $field_data['class'] : '';
			$class_attr = ' class="fmwp-forms-field ' . $class . '" ';

			$data = [
				'field_id' => $field_data['id'],
			];

			if ( ! empty( $field_data['data'] ) ) {
				$data = array_merge( $data, $field_data['data'] );
			}

			$data_attr = '';
			foreach ( $data as $key => $value ) {
				$data_attr .= " data-{$key}=\"{$value}\" ";
			}

			$name = $field_data['id'];
			$name = ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] . '[' . $name . ']' : $name;
			$name_attr = ' name="' . $name . '" ';

			$value = $this->get_field_value( $field_data );
			$value_attr = ' value="' . $value . '" ';

			$html = "<input type=\"hidden\" $id_attr $class_attr $name_attr $data_attr $value_attr />";

			return $html;
		}


		/**
		 * Render text field
		 *
		 * @param $field_data
		 *
		 * @return bool|string
		 */
		function render_text( $field_data ) {

			if ( empty( $field_data['id'] ) )
				return false;

			$id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $field_data['id'];
			$id_attr = ' id="' . $id . '" ';

			$class = ! empty( $field_data['class'] ) ? $field_data['class'] : '';
			$class .= ! empty( $field_data['size'] ) ? 'fmwp-' . $field_data['size'] . '-field' : 'fmwp-long-field';
			$class_attr = ' class="fmwp-forms-field ' . $class . '" ';

			$data = [
				'field_id' => $field_data['id'],
			];

			$data_attr = '';
			foreach ( $data as $key => $value ) {
				$data_attr .= " data-{$key}=\"{$value}\" ";
			}

			$placeholder_attr = ! empty( $field_data['placeholder'] ) ? ' placeholder="' . $field_data['placeholder'] . '"' : '';

			$name = $field_data['id'];
			$name = ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] . '[' . $name . ']' : $name;
			$name_attr = ' name="' . $name . '" ';

			$value = $this->get_field_value( $field_data );
			$value_attr = ' value="' . esc_attr( $value ) . '" ';

			$html = "<input type=\"text\" $id_attr $class_attr $name_attr $data_attr $value_attr $placeholder_attr />";

			return $html;
		}


		/**
		 * Render text field
		 *
		 * @param $field_data
		 *
		 * @return bool|string
		 */
		function render_icon_select( $field_data ) {

			if ( empty( $field_data['id'] ) ) {
				return false;
			}

			$id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $field_data['id'];
			$id_attr = ' id="' . $id . '" ';

			$class = ! empty( $field_data['class'] ) ? $field_data['class'] : '';
			$class .= ! empty( $field_data['size'] ) ? 'fmwp-' . $field_data['size'] . '-field' : 'fmwp-long-field';
			$class_attr = ' class="fmwp-forms-field fmwp-icon-select-field ' . $class . '" ';

			$data = [
				'field_id' => $field_data['id'],
			];

			$data_attr = '';
			foreach ( $data as $key => $value ) {
				$data_attr .= " data-{$key}=\"{$value}\" ";
			}

			$name = $field_data['id'];
			$name = ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] . '[' . $name . ']' : $name;
			$name_attr = ' name="' . $name . '" ';

			$value = $this->get_field_value( $field_data );
			$value_attr = ' data-value="' . esc_attr( $value ) . '" ';

			$html = "<select $id_attr $name_attr $class_attr $data_attr $value_attr></select>";

			return $html;
		}


		/**
		 * Render number field
		 *
		 * @param $field_data
		 *
		 * @return bool|string
		 */
		function render_number( $field_data ) {

			if ( empty( $field_data['id'] ) )
				return false;

			$id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $field_data['id'];
			$id_attr = ' id="' . $id . '" ';

			$class = ! empty( $field_data['class'] ) ? $field_data['class'] : '';
			$class .= ! empty( $field_data['size'] ) ? 'fmwp-' . $field_data['size'] . '-field' : 'fmwp-long-field';
			$class_attr = ' class="fmwp-forms-field ' . $class . '" ';

			$data = [
				'field_id' => $field_data['id'],
			];

			$data_attr = '';
			foreach ( $data as $key => $value ) {
				$data_attr .= " data-{$key}=\"{$value}\" ";
			}

			$placeholder_attr = ! empty( $field_data['placeholder'] ) ? ' placeholder="' . $field_data['placeholder'] . '"' : '';

			$name = $field_data['id'];
			$name = ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] . '[' . $name . ']' : $name;
			$name_attr = ' name="' . $name . '" ';

			$value = $this->get_field_value( $field_data );
			$value_attr = ' value="' . esc_attr( $value ) . '" ';

			$html = "<input type=\"number\" $id_attr $class_attr $name_attr $data_attr $value_attr $placeholder_attr />";

			return $html;
		}


		/**
		 * @param $field_data
		 *
		 * @return bool|string
		 */
		function render_color( $field_data ) {

			if ( empty( $field_data['id'] ) ) {
				return false;
			}

			$id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $field_data['id'];
			$id_attr = ' id="' . $id . '" ';

			$class = ! empty( $field_data['class'] ) ? $field_data['class'] : '';
			$class .= ! empty( $field_data['size'] ) ? ' fmwp-' . $field_data['size'] . '-field ' : ' fmwp-long-field ';
			$class .= ' fmwp-admin-colorpicker ';
			$class_attr = ' class="fmwp-forms-field ' . $class . '" ';

			$data = [
				'field_id' => $field_data['id'],
			];

			$data_attr = '';
			foreach ( $data as $key => $value ) {
				$data_attr .= " data-{$key}=\"{$value}\" ";
			}

			$placeholder_attr = ! empty( $field_data['placeholder'] ) ? ' placeholder="' . $field_data['placeholder'] . '"' : '';

			$name = $field_data['id'];
			$name = ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] . '[' . $name . ']' : $name;
			$name_attr = ' name="' . $name . '" ';

			$value = $this->get_field_value( $field_data );
			$value_attr = ' value="' . $value . '" ';

			$html = "<input type=\"text\" $id_attr $class_attr $name_attr $data_attr $value_attr $placeholder_attr />";

			return $html;
		}


		/**
		 * @param $field_data
		 *
		 * @return bool|string
		 */
		function render_textarea( $field_data ) {

			if ( empty( $field_data['id'] ) )
				return false;

			$id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $field_data['id'];
			$id_attr = ' id="' . $id . '" ';

			$class = ! empty( $field_data['class'] ) ? $field_data['class'] : '';
			$class .= ! empty( $field_data['size'] ) ? $field_data['size'] : 'fmwp-long-field';
			$class_attr = ' class="fmwp-forms-field ' . $class . '" ';

			$data = [
				'field_id' => $field_data['id'],
			];

			$data_attr = '';
			foreach ( $data as $key => $value ) {
				$data_attr .= " data-{$key}=\"{$value}\" ";
			}

			$rows = ! empty( $field_data['args']['textarea_rows'] ) ? ' rows="' . $field_data['args']['textarea_rows'] . '" ' : '';

			$name = $field_data['id'];
			$name = ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] . '[' . $name . ']' : $name;
			$name_attr = ' name="' . $name . '" ';

			$value = $this->get_field_value( $field_data );

			$html = "<textarea $id_attr $class_attr $name_attr $data_attr $rows>$value</textarea>";

			return $html;
		}


		/**
		 * @param $field_data
		 *
		 * @return bool|string
		 */
		function render_checkbox( $field_data ) {

			if ( empty( $field_data['id'] ) )
				return false;

			$id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $field_data['id'];
			$id_attr = ' id="' . $id . '" ';
			$id_attr_hidden = ' id="' . $id . '_hidden" ';

			$class = ! empty( $field_data['class'] ) ? $field_data['class'] : '';
			$class .= ! empty( $field_data['size'] ) ? $field_data['size'] : 'fmwp-long-field';
			$class_attr = ' class="fmwp-forms-field ' . $class . '" ';

			$data = [
				'field_id' => $field_data['id'],
			];

			$data_attr = '';
			foreach ( $data as $key => $value ) {
				$data_attr .= " data-{$key}=\"{$value}\" ";
			}

			$name = $field_data['id'];
			$name = ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] . '[' . $name . ']' : $name;
			$name_attr = ' name="' . $name . '" ';

			$value = $this->get_field_value( $field_data );

			$html = "<input type=\"hidden\" $id_attr_hidden $name_attr value=\"0\" />
			<input type=\"checkbox\" $id_attr $class_attr $name_attr $data_attr " . checked( $value, true, false ) . " value=\"1\" />";


			return $html;
		}


		/**
		 * @param $field_data
		 *
		 * @return bool|string
		 */
		function render_select( $field_data ) {

			if ( empty( $field_data['id'] ) )
				return false;

			$multiple = ! empty( $field_data['multi'] ) ? 'multiple' : '';

			$id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $field_data['id'];
			$id_attr = ' id="' . $id . '" ';

			$class = ! empty( $field_data['class'] ) ? $field_data['class'] : '';
			$class .= ! empty( $field_data['size'] ) ? 'fmwp-' . $field_data['size'] . '-field' : 'fmwp-long-field';
			$class_attr = ' class="fmwp-forms-field ' . $class . '" ';

			$data = [
				'field_id' => $field_data['id']
			];

			$data_attr = '';
			foreach ( $data as $key => $value ) {
				$data_attr .= " data-{$key}=\"{$value}\" ";
			}

			$name = $field_data['id'];
			$name = ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] . '[' . $name . ']' : $name;
			$hidden_name_attr = ' name="' . $name . '" ';
			$name = $name . ( ! empty( $field_data['multi'] ) ? '[]' : '' );
			$name_attr = ' name="' . $name . '" ';

			$value = $this->get_field_value( $field_data );

			$options = '';
			if ( ! empty( $field_data['options'] ) ) {
				foreach ( $field_data['options'] as $key => $option ) {
					if ( ! empty( $field_data['multi'] ) ) {

						if ( ! is_array( $value ) || empty( $value ) ) {
							$value = [];
						}

						$options .= '<option value="' . $key . '" ' . selected( in_array( $key, $value ), true, false ) . '>' . esc_html( $option ) . '</option>';
					} else {
						$options .= '<option value="' . $key . '" ' . selected( (string)$key == $value, true, false ) . '>' . esc_html( $option ) . '</option>';
					}
				}
			}

			$hidden = '';
			if ( ! empty( $multiple ) ) {
				$hidden = "<input type=\"hidden\" $hidden_name_attr value=\"\" />";
			}
			$html = "$hidden<select $multiple $id_attr $name_attr $class_attr $data_attr>$options</select>";

			return $html;
		}


		/**
		 * @param $field_data
		 *
		 * @return bool|string
		 */
		function render_multi_checkbox( $field_data ) {

			if ( empty( $field_data['id'] ) )
				return false;

			$id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $field_data['id'];

			$class = ! empty( $field_data['class'] ) ? $field_data['class'] : '';
			$class .= ! empty( $field_data['size'] ) ? $field_data['size'] : 'fmwp-long-field';
			$class_attr = ' class="fmwp-forms-field ' . $class . '" ';

			$name = $field_data['id'];
			$name = ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] . '[' . $name . ']' : $name;

			$values = $this->get_field_value( $field_data );

			$i = 0;
			$html = "<input type=\"hidden\" name=\"$name\" value=\"\" />";

			$columns = ( ! empty( $field_data['columns'] ) && is_numeric( $field_data['columns'] ) ) ? $field_data['columns'] : 1;
			while ( $i < $columns ) {
				$per_page = ceil( count( $field_data['options'] ) / $columns );
				$section_fields_per_page = array_slice( $field_data['options'], $i*$per_page, $per_page );
				$html .= '<span class="fmwp-form-fields-section" style="width:' . floor( 100 / $columns ) . '% !important;">';

				foreach ( $section_fields_per_page as $k => $title ) {
					$id_attr = ' id="' . $id . '_' . $k . '" ';
					$for_attr = ' for="' . $id . '_' . $k . '" ';
					$name_attr = ' name="' . $name . '[' . $k . ']" ';

					$html .= "<label $for_attr>
						<input type=\"checkbox\" " . checked( in_array( $k, $values ), true, false ) . "$id_attr $name_attr value=\"1\" $class_attr>
						<span>$title</span>
					</label>";
				}

				$html .= '</span>';
				$i++;
			}

			return $html;
		}


		/**
		 * @param $field_data
		 *
		 * @return bool|string
		 */
		function render_media( $field_data ) {

			if ( empty( $field_data['id'] ) )
				return false;

			$id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $field_data['id'];

			$class = ! empty( $field_data['class'] ) ? $field_data['class'] : '';
			$class .= ! empty( $field_data['size'] ) ? $field_data['size'] : 'fmwp-long-field';
			$class_attr = ' class="fmwp-forms-field fmwp-media-upload-data-url ' . $class . '"';

			$data = [
				'field_id' => $field_data['id'] . '_url',
			];

			if ( ! empty( $field_data['default']['url'] ) )
				$data['default'] = esc_attr( $field_data['default']['url'] );

			$data_attr = '';
			foreach ( $data as $key => $value ) {
				$data_attr .= " data-{$key}=\"{$value}\" ";
			}

			$name = $field_data['id'];
			$name = ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] . '[' . $name . ']' : $name;

			$value = $this->get_field_value( $field_data );

			$upload_frame_title = ! empty( $field_data['upload_frame_title'] ) ? $field_data['upload_frame_title'] : __( 'Select media', 'forumwp' );

			$image_id = ! empty( $value['id'] ) ? $value['id'] : '';
			$image_width = ! empty( $value['width'] ) ? $value['width'] : '';
			$image_height = ! empty( $value['height'] ) ? $value['height'] : '';
			$image_thumbnail = ! empty( $value['thumbnail'] ) ? $value['thumbnail'] : '';
			$image_url = ! empty( $value['url'] ) ? $value['url'] : '';

			$html = "<div class=\"fmwp-media-upload\">" .
					"<input type=\"hidden\" class=\"fmwp-media-upload-data-id\" name=\"{$name}[id]\" id=\"{$id}_id\" value=\"$image_id\">" .
					"<input type=\"hidden\" class=\"fmwp-media-upload-data-width\" name=\"{$name}[width]\" id=\"{$id}_width\" value=\"$image_width\">" .
					"<input type=\"hidden\" class=\"fmwp-media-upload-data-height\" name=\"{$name}[height]\" id=\"{$id}_height\" value=\"$image_height\">" .
					"<input type=\"hidden\" class=\"fmwp-media-upload-data-thumbnail\" name=\"{$name}[thumbnail]\" id=\"{$id}_thumbnail\" value=\"$image_thumbnail\">" .
					"<input type=\"hidden\" $class_attr name=\"{$name}[url]\" id=\"{$id}_url\" value=\"$image_url\" $data_attr>";

			if ( ! isset( $field_data['preview'] ) || $field_data['preview'] !== false ) {
				$html .= '<img src="' . $image_url . '" alt="" class="icon_preview"><div style="clear:both;"></div>';
			}

			if ( ! empty( $field_data['url'] ) ) {
				$html .= '<input type="text" class="fmwp-media-upload-url" readonly value="' . $image_url . '" /><div style="clear:both;"></div>';
			}

			$html .= '<input type="button" class="fmwp-set-image button button-primary" value="' . __( 'Select', 'forumwp' ) . '" data-upload_frame="' . $upload_frame_title . '" />
					<input type="button" class="fmwp-clear-image button" value="' . __( 'Clear', 'forumwp' ) . '" /></div>';

			return $html;
		}


		/**
		 * @param $field_data
		 *
		 * @return bool|string
		 */
		function render_email_template( $field_data ) {
			if ( empty( $field_data['id'] ) )
				return false;

			$id = ( ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] : '' ) . '_' . $field_data['id'];

			$class = ! empty( $field_data['class'] ) ? $field_data['class'] : '';
			$class .= ! empty( $field_data['size'] ) ? $field_data['size'] : 'fmwp-long-field';

			$data = [
				'field_id' => $field_data['id']
			];

			$data_attr = '';
			foreach ( $data as $key => $value ) {
				$data_attr .= " data-{$key}=\"{$value}\" ";
			}

			$name = $field_data['id'];
			$name = ! empty( $this->form_data['prefix_id'] ) ? $this->form_data['prefix_id'] . '[' . $name . ']' : $name;

			$value = $this->get_field_value( $field_data );

			ob_start(); ?>

			<div class="email_template_wrapper" data-key="<?php echo $field_data['id'] ?>" style="position: relative;">
				<?php wp_editor( $value,
					$id,
					[
						'textarea_name' => $name,
						'textarea_rows' => 20,
						'editor_height' => 425,
						'wpautop'       => false,
						'media_buttons' => false,
						'editor_class'  => $class
					]
				); ?>
			</div>

			<?php $html = ob_get_clean();

			return $html;
		}


		/**
		 * @param $field_data
		 *
		 * @return mixed
		 */
		function render_info_text( $field_data ) {
			return $field_data['value'];
		}


		/**
		 * @param $field_data
		 *
		 * @return mixed
		 */
		function render_separator( $field_data ) {
			return $field_data['value'] . '<hr />';
		}


		/**
		 * Get field value
		 *
		 * @param array $field_data
		 * @param string $i
		 * @return string|array
		 */
		function get_field_value( $field_data, $i = '' ) {
			$default = ( $field_data['type'] == 'multi_checkbox' ) ? [] : '';
			$default = isset( $field_data[ 'default' . $i ] ) ? $field_data[ 'default' . $i ] : $default;

			if ( $field_data['type'] == 'checkbox' || $field_data['type'] == 'multi_checkbox' ) {
				$value = ( isset( $field_data[ 'value' . $i ] ) && '' !== $field_data[ 'value' . $i ] ) ? $field_data[ 'value' . $i ] : $default;
			} else {
				$value = isset( $field_data[ 'value' . $i ] ) ? $field_data[ 'value' . $i ] : $default;
			}

			$value = is_string( $value ) ? stripslashes( $value ) : $value;

			return $value;
		}
	}
}