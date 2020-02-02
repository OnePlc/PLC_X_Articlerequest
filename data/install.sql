--
-- Base Table
--
CREATE TABLE `articlerequest` (
  `Articlerequest_ID` int(11) NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `articlerequest`
  ADD PRIMARY KEY (`Articlerequest_ID`);

ALTER TABLE `articlerequest`
  MODIFY `Articlerequest_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Permissions
--
INSERT INTO `permission` (`permission_key`, `module`, `label`, `nav_label`, `nav_href`, `show_in_menu`) VALUES
('add', 'OnePlace\\Articlerequest\\Controller\\ArticlerequestController', 'Add', '', '', 0),
('edit', 'OnePlace\\Articlerequest\\Controller\\ArticlerequestController', 'Edit', '', '', 0),
('index', 'OnePlace\\Articlerequest\\Controller\\ArticlerequestController', 'Index', 'Articlerequests', '/articlerequest', 1),
('list', 'OnePlace\\Articlerequest\\Controller\\ApiController', 'List', '', '', 1),
('view', 'OnePlace\\Articlerequest\\Controller\\ArticlerequestController', 'View', '', '', 0),
('success', 'OnePlace\\Articlerequest\\Controller\\ArticlerequestController', 'Close as successful', '', '', 0);

--
-- Form
--
INSERT INTO `core_form` (`form_key`, `label`, `entity_class`, `entity_tbl_class`) VALUES
('articlerequest-single', 'Articlerequest', 'OnePlace\\Articlerequest\\Model\\Articlerequest', 'OnePlace\\Articlerequest\\Model\\ArticlerequestTable');

--
-- Index List
--
INSERT INTO `core_index_table` (`table_name`, `form`, `label`) VALUES
('articlerequest-index', 'articlerequest-single', 'Articlerequest Index');

--
-- Tabs
--
INSERT INTO `core_form_tab` (`Tab_ID`, `form`, `title`, `subtitle`, `icon`, `counter`, `sort_id`, `filter_check`, `filter_value`) VALUES ('articlerequest-base', 'articlerequest-single', 'Articlerequest', 'Base', 'fas fa-cogs', '', '0', '', '');

--
-- Buttons
--
INSERT INTO `core_form_button` (`Button_ID`, `label`, `icon`, `title`, `href`, `class`, `append`, `form`, `mode`, `filter_check`, `filter_value`) VALUES
(NULL, 'Save Articlerequest', 'fas fa-save', 'Save Articlerequest', '#', 'primary saveForm', '', 'articlerequest-single', 'link', '', ''),
(NULL, 'Edit Articlerequest', 'fas fa-edit', 'Edit Articlerequest', '/articlerequest/edit/##ID##', 'primary', '', 'articlerequest-view', 'link', '', ''),
(NULL, 'Add Articlerequest', 'fas fa-plus', 'Add Articlerequest', '/articlerequest/add', 'primary', '', 'articlerequest-index', 'link', '', '');

--
-- Fields
--
INSERT INTO `core_form_field` (`Field_ID`, `type`, `label`, `fieldkey`, `tab`, `form`, `class`, `url_view`, `url_list`, `show_widget_left`, `allow_clear`, `readonly`, `tbl_cached_name`, `tbl_class`, `tbl_permission`) VALUES
(NULL, 'text', 'Name', 'label', 'articlerequest-base', 'articlerequest-single', 'col-md-3', '/articlerequest/view/##ID##', '', 0, 1, 0, '', '', '');

--
-- Request Criteria
--
CREATE TABLE `articlerequest_criteria` (
  `Criteria_ID` int(11) NOT NULL,
  `criteria_entity_key` varchar(100) NOT NULL,
  `label` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `compare_notice` tinyint(1) NOT NULL DEFAULT 0,
  `articlerequest_field` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `articlerequest_criteria`
  ADD PRIMARY KEY (`Criteria_ID`);

ALTER TABLE `articlerequest_criteria`
  MODIFY `Criteria_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Widget
--
INSERT INTO `core_widget` (`Widget_ID`, `widget_name`, `label`, `permission`) VALUES
(NULL, 'articlerequest_matching', 'Matching Results', 'index-Application\\Controller\\IndexController');

COMMIT;