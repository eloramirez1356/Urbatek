(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["ticket"],{

/***/ "./assets/js/ticket.js":
/*!*****************************!*\
  !*** ./assets/js/ticket.js ***!
  \*****************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("/* WEBPACK VAR INJECTION */(function($) {$(document).ready(function () {\n  $material_element = $('#ticket_material');\n  $material_element.ready(function () {\n    setMaterialSelector($('option:selected', this).attr('js-type'));\n  });\n  setMaterialSelector($('option:selected', $material_element).attr('js-type'));\n  $material_element.on('change', function () {\n    var type = $('option:selected', this).attr('js-type');\n    setMaterialSelector(type);\n  });\n});\n\nfunction setMaterialSelector(type) {\n  var $tons_selector = $('#ticket_tons');\n  var $num_travels_selector = $('#ticket_num_travels');\n\n  if (type === 'withdrawal') {\n    $num_travels_selector.parent().show();\n    $num_travels_selector.attr('required', true);\n    $num_travels_selector.attr('disabled', false);\n    $tons_selector.parent().hide();\n    $tons_selector.attr('required', false);\n    $tons_selector.attr('disabled', true);\n  }\n\n  if (type === 'supply') {\n    $tons_selector.parent().show();\n    $tons_selector.attr('required', true);\n    $tons_selector.attr('disabled', false);\n    $num_travels_selector.parent().hide();\n    $num_travels_selector.attr('required', false);\n    $num_travels_selector.attr('disabled', true);\n  }\n}\n/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ \"./node_modules/jquery/dist/jquery.js\")))\n\n//# sourceURL=webpack:///./assets/js/ticket.js?");

/***/ })

},[["./assets/js/ticket.js","runtime","vendors~admin~app~login~search~table2csv~ticket"]]]);