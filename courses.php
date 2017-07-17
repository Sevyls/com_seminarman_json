<?php

  /*
   * Produces JSON from Joomla's OSG Seminar manager data (com_seminarman)
   */
  $mysqli = new mysqli("localhost", "username", "password", "database");
  $prefix = "table_prefix";

  $data = array();

  $result = $mysqli->query(
    "SELECT title, code, alias, reference_number, id,
    location, min_attend, capacity, start_date, start_time, finish_date, finish_time,
    url, modified, created, version
    FROM " . $prefix . "_seminarman_courses ORDER BY start_date desc");

  $courses = array();
  while ($row = $result->fetch_assoc()) {
      $courses[] = $row;
  }
  $result->close();

  $data['courses'] = $courses;

  $result = $mysqli->query(
    "SELECT a.id, a.title, a.first_name, a.last_name, a.salutation, a.email, a.course_id
    FROM " . $prefix . "_seminarman_application a
     ORDER BY date desc");

  $applications = array();

  while ($row = $result->fetch_assoc()) {
      $applications[] = $row;
  }
  $data['applications'] = $applications;
  $result->close();

  $result = $mysqli->query(
    "SELECT *
    FROM " . $prefix . "_seminarman_fields
    WHERE published = 1");

  $fields = array();
  while ($row = $result->fetch_assoc()) {
      $fields[] = $row;
  }
  $data['fields'] = $fields;
  $result->close();

  $result = $mysqli->query(
    "SELECT applicationid, field_id, value
    FROM " . $prefix . "_seminarman_fields_values
    WHERE value is not null and value != ''");

  $fields_values = array();
  while ($row = $result->fetch_assoc()) {
      $fields_values[] = $row;
  }
  $data['fields_values'] = $fields_values;

  $result->close();

  $mysqli->close();

  header('Content-Type: application/json');
  echo json_encode($data);
?>
