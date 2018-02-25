!function(t,e,i){"use strict";function s(t){var e=this;return!this.rows.contains(function(i){return e.identifier&&i[e.identifier]===t[e.identifier]})&&(this.rows.push(t),!0)}function n(e){var i=this.footer?this.footer.find(e):t(),s=this.header?this.header.find(e):t();return t.merge(i,s)}function o(e){return e?t.extend({},this.cachedParams,{ctx:e}):this.cachedParams}function r(){var e={current:this.current,rowCount:this.rowCount,sort:this.sortDictionary,searchPhrase:this.searchPhrase},i=this.options.post;return i=t.isFunction(i)?i():i,this.options.requestHandler(t.extend(!0,e,i))}function l(e){return"."+t.trim(e).replace(/\s+/gm,".")}function a(){var e=this.options.url;return t.isFunction(e)?e():e}function c(){this.element.trigger("initialize"+M),u.call(this),this.selection=this.options.selection&&null!=this.identifier,f.call(this),v.call(this),_.call(this),S.call(this),m.call(this),p.call(this),this.element.trigger("initialized"+M)}function h(t){this.options.highlightRows}function d(t){return t.visible}function u(){var e=this,i=!1;this.element.find("thead > tr").first().children().each(function(){var s=t(this),n=s.data(),o={id:n.columnId,identifier:null==e.identifier&&n.identifier||!1,converter:e.options.converters[n.converter||n.type]||e.options.converters.string,text:s.text(),align:n.align||"left",headerAlign:n.headerAlign||"left",cssClass:n.cssClass||"",headerCssClass:n.headerCssClass||"",formatter:e.options.formatters[n.formatter]||null,order:i||"asc"!==n.order&&"desc"!==n.order?null:n.order,searchable:!(!1===n.searchable),sortable:!(!1===n.sortable),visible:!(!1===n.visible),visibleInSelection:!(!1===n.visibleInSelection),width:t.isNumeric(n.width)?n.width+"px":"string"==typeof n.width?n.width:null};e.columns.push(o),null!=o.order&&(e.sortDictionary[o.id]=o.order),o.identifier&&(e.identifier=o.id,e.converter=o.converter),e.options.multiSort||null===o.order||(i=!0)})}function p(){function i(t,e){s.currentRows=t,g.call(s,e),s.options.keepSelection||(s.selectedRows=[]),A.call(s,t),w.call(s),x.call(s),s.element._bgBusyAria(!1).trigger("loaded"+M)}var s=this;if(this.element._bgBusyAria(!0).trigger("load"+M),B.call(this),this.options.ajax){var n=r.call(this),o=a.call(this);if(null==o||"string"!=typeof o||0===o.length)throw new Error("Url setting must be a none empty string or a function that returns one.");this.xqr&&this.xqr.abort();var l={url:o,data:n,success:function(e){s.xqr=null,"string"==typeof e&&(e=t.parseJSON(e)),e=s.options.responseHandler(e),s.current=e.current,i(e.rows,e.total)},error:function(t,e,i){s.xqr=null,"abort"!==e&&(y.call(s),s.element._bgBusyAria(!1).trigger("loaded"+M))}};l=t.extend(this.options.ajaxSettings,l),this.xqr=t.ajax(l)}else{var c=this.searchPhrase.length>0?this.rows.where(function(t){for(var e,i=new RegExp(s.searchPhrase,s.options.caseSensitive?"g":"gi"),n=0;n<s.columns.length;n++)if((e=s.columns[n]).searchable&&e.visible&&e.converter.to(t[e.id]).search(i)>-1)return!0;return!1}):this.rows,h=c.length;-1!==this.rowCount&&(c=c.page(this.current,this.rowCount)),e.setTimeout(function(){i(c,h)},10)}}function f(){if(!this.options.ajax){var e=this;this.element.find("tbody > tr").each(function(){var i=t(this).children("td"),n={};t.each(e.columns,function(t,e){n[e.id]=e.converter.from(i.eq(t).text())}),s.call(e,n)}),g.call(this,this.rows.length),j.call(this)}}function g(t){this.total=t,this.totalPages=-1===this.rowCount?1:Math.ceil(this.total/this.rowCount)}function v(){var e=this.options.templates,i=this.element.parent().hasClass(this.options.css.responsiveTable)?this.element.parent():this.element;this.element.addClass(this.options.css.table),0===this.element.children("tbody").length&&this.element.append(e.body),1&this.options.navigation&&(this.header=t(e.header.resolve(o.call(this,{id:this.element._bgId()+"-header"}))),i.before(this.header)),2&this.options.navigation&&(this.footer=t(e.footer.resolve(o.call(this,{id:this.element._bgId()+"-footer"}))),i.after(this.footer))}function m(){if(0!==this.options.navigation){var e=this.options.css,i=l(e.actions),s=n.call(this,i);if(s.length>0){var r=this,a=this.options.templates,c=t(a.actions.resolve(o.call(this)));if(this.options.ajax){var h=a.icon.resolve(o.call(this,{iconCss:e.iconRefresh})),d=t(a.actionButton.resolve(o.call(this,{content:h,text:this.options.labels.refresh}))).on("click"+M,function(t){t.stopPropagation(),r.current=1,p.call(r)});c.append(d)}D.call(this,c),b.call(this,c),P.call(this,s,c)}}}function b(e){if(this.options.columnSelection&&this.columns.length>1){var i=this,s=this.options.css,n=this.options.templates,r=n.icon.resolve(o.call(this,{iconCss:s.iconColumns})),a=t(n.actionDropDown.resolve(o.call(this,{content:r}))),c=l(s.dropDownItem),h=l(s.dropDownItemCheckbox),u=l(s.dropDownMenuItems);t.each(this.columns,function(e,r){if(r.visibleInSelection){var f=t(n.actionDropDownCheckboxItem.resolve(o.call(i,{name:r.id,label:r.text,checked:r.visible}))).on("click"+M,c,function(e){e.stopPropagation();var s=t(this),n=s.find(h);if(!n.prop("disabled")){r.visible=n.prop("checked");var o=i.columns.where(d).length>1;s.parents(u).find(c+":has("+h+":checked)")._bgEnableAria(o).find(h)._bgEnableField(o),i.element.find("tbody").empty(),_.call(i),p.call(i)}});a.find(l(s.dropDownMenuItems)).append(f)}}),e.append(a)}}function w(){if(0!==this.options.navigation){var e=l(this.options.css.infos),i=n.call(this,e);if(i.length>0){var s=this.current*this.rowCount,r=t(this.options.templates.infos.resolve(o.call(this,{end:0===this.total||-1===s||s>this.total?this.total:s,start:0===this.total?0:s-this.rowCount+1,total:this.total})));P.call(this,i,r)}}}function y(){var t=this.element.children("tbody").first(),e=this.options.templates,i=this.columns.where(d).length;this.selection&&(i+=1),t.html(e.noResults.resolve(o.call(this,{columns:i})))}function x(){if(0!==this.options.navigation){var e=l(this.options.css.pagination),i=n.call(this,e)._bgShowAria(-1!==this.rowCount);if(-1!==this.rowCount&&i.length>0){var s=this.options.templates,r=this.current,a=this.totalPages,c=t(s.pagination.resolve(o.call(this))),h=a-r,d=-1*(this.options.padding-r),u=h>=this.options.padding?Math.max(d,1):Math.max(d-this.options.padding+h,1),p=2*this.options.padding+1,f=a>=p?p:a;C.call(this,c,"first","&laquo;","first")._bgEnableAria(r>1),C.call(this,c,"prev","&lt;","prev")._bgEnableAria(r>1);for(var g=0;g<f;g++){var v=g+u;C.call(this,c,v,v,"page-"+v)._bgEnableAria()._bgSelectAria(v===r)}0===f&&C.call(this,c,1,1,"page-1")._bgEnableAria(!1)._bgSelectAria(),C.call(this,c,"next","&gt;","next")._bgEnableAria(a>r),C.call(this,c,"last","&raquo;","last")._bgEnableAria(a>r),P.call(this,i,c)}}}function C(e,i,s,n){var r=this,a=this.options.templates,c=this.options.css,h=o.call(this,{css:n,text:s,page:i}),d=t(a.paginationItem.resolve(h)).on("click"+M,l(c.paginationButton),function(e){e.stopPropagation(),e.preventDefault();var i=t(this),s=i.parent();if(!s.hasClass("active")&&!s.hasClass("disabled")){var n={first:1,prev:r.current-1,next:r.current+1,last:r.totalPages},o=i.data("page");r.current=n[o]||o,p.call(r)}i.trigger("blur")});return e.append(d),d}function D(e){function i(t){return-1===t?s.options.labels.all:t}var s=this,n=this.options.rowCount;if(t.isArray(n)){var r=this.options.css,a=this.options.templates,c=t(a.actionDropDown.resolve(o.call(this,{content:i(this.rowCount)}))),h=l(r.dropDownMenu),d=l(r.dropDownMenuText),u=l(r.dropDownMenuItems),f=l(r.dropDownItemButton);t.each(n,function(e,n){var r=t(a.actionDropDownItem.resolve(o.call(s,{text:i(n),action:n})))._bgSelectAria(n===s.rowCount).on("click"+M,f,function(e){e.preventDefault();var n=t(this),o=n.data("action");o!==s.rowCount&&(s.current=1,s.rowCount=o,n.parents(u).children().each(function(){var e=t(this),i=e.find(f).data("action");e._bgSelectAria(i===o)}),n.parents(h).find(d).text(i(o)),p.call(s))});c.find(u).append(r)}),e.append(c)}}function A(e){if(e.length>0){var i=this,s=this.options.css,n=this.options.templates,r=this.element.children("tbody").first(),a=!0,c="";t.each(e,function(e,r){var l="",h=' data-row-id="'+(null==i.identifier?e:r[i.identifier])+'"',d="";if(i.selection){var u=-1!==t.inArray(r[i.identifier],i.selectedRows),p=n.select.resolve(o.call(i,{type:"checkbox",value:r[i.identifier],checked:u}));l+=n.cell.resolve(o.call(i,{content:p,css:s.selectCell})),a=a&&u,u&&(d+=s.selected,h+=' aria-selected="true"')}var f=null!=r.status&&i.options.statusMapping[r.status];f&&(d+=f),t.each(i.columns,function(e,a){if(a.visible){var c=t.isFunction(a.formatter)?a.formatter.call(i,a,r):a.converter.to(r[a.id]),h=a.cssClass.length>0?" "+a.cssClass:"";l+=n.cell.resolve(o.call(i,{content:null==c||""===c?"&nbsp;":c,css:("right"===a.align?s.right:"center"===a.align?s.center:s.left)+h,style:null==a.width?"":"width:"+a.width+";"}))}}),d.length>0&&(h+=' class="'+d+'"'),c+=n.row.resolve(o.call(i,{attr:h,cells:l}))}),i.element.find("thead "+l(i.options.css.selectBox)).prop("checked",a),r.html(c),k.call(this,r)}else y.call(this)}function k(e){var i=this,s=l(this.options.css.selectBox);this.selection&&e.off("click"+M,s).on("click"+M,s,function(e){e.stopPropagation();var s=t(this),n=i.converter.from(s.val());s.prop("checked")?i.select([n]):i.deselect([n])}),e.off("click"+M,"> tr").on("click"+M,"> tr",function(e){e.stopPropagation();var s=t(this),n=null==i.identifier?s.data("row-id"):i.converter.from(s.data("row-id")+""),o=null==i.identifier?i.currentRows[n]:i.currentRows.first(function(t){return t[i.identifier]===n});i.selection&&i.options.rowSelect&&(s.hasClass(i.options.css.selected)?i.deselect([n]):i.select([n])),i.element.trigger("click"+M,[i.columns,o])})}function S(){if(0!==this.options.navigation){var i=this.options.css,s=l(i.search),r=n.call(this,s);if(r.length>0){var a=this,c=this.options.templates,h=null,d="",u=l(i.searchField),p=t(c.search.resolve(o.call(this)));(p.is(u)?p:p.find(u)).on("keyup"+M,function(i){i.stopPropagation();var s=t(this).val();(d!==s||13===i.which&&""!==s)&&(d=s,(13===i.which||0===s.length||s.length>=a.options.searchSettings.characters)&&(e.clearTimeout(h),h=e.setTimeout(function(){R.call(a,s)},a.options.searchSettings.delay)))}),P.call(this,r,p)}}}function R(t){this.searchPhrase!==t&&(this.current=1,this.searchPhrase=t,p.call(this))}function _(){var e=this,i=this.element.find("thead > tr"),s=this.options.css,n=this.options.templates,r="",a=this.options.sorting;if(this.selection){var c=this.options.multiSelect?n.select.resolve(o.call(e,{type:"checkbox",value:"all"})):"";r+=n.rawHeaderCell.resolve(o.call(e,{content:c,css:s.selectCell}))}if(t.each(this.columns,function(t,i){if(i.visible){var l=e.sortDictionary[i.id],c=a&&l&&"asc"===l?s.iconUp:a&&l&&"desc"===l?s.iconDown:"",h=n.icon.resolve(o.call(e,{iconCss:c})),d=i.headerAlign,u=i.headerCssClass.length>0?" "+i.headerCssClass:"";r+=n.headerCell.resolve(o.call(e,{column:i,icon:h,sortable:a&&i.sortable&&s.sortable||"",css:("right"===d?s.right:"center"===d?s.center:s.left)+u,style:null==i.width?"":"width:"+i.width+";"}))}}),i.html(r),a){var h=l(s.sortable);i.off("click"+M,h).on("click"+M,h,function(i){i.preventDefault(),I.call(e,t(this)),j.call(e),p.call(e)})}if(this.selection&&this.options.multiSelect){var d=l(s.selectBox);i.off("click"+M,d).on("click"+M,d,function(i){i.stopPropagation(),t(this).prop("checked")?e.select():e.deselect()})}}function I(t){var e=this.options.css,i=l(e.icon),s=t.data("column-id")||t.parents("th").first().data("column-id"),n=this.sortDictionary[s],o=t.find(i);if(this.options.multiSort||(t.parents("tr").first().find(i).removeClass(e.iconDown+" "+e.iconUp),this.sortDictionary={}),n&&"asc"===n)this.sortDictionary[s]="desc",o.removeClass(e.iconUp).addClass(e.iconDown);else if(n&&"desc"===n)if(this.options.multiSort){var r={};for(var a in this.sortDictionary)a!==s&&(r[a]=this.sortDictionary[a]);this.sortDictionary=r,o.removeClass(e.iconDown)}else this.sortDictionary[s]="asc",o.removeClass(e.iconDown).addClass(e.iconUp);else this.sortDictionary[s]="asc",o.addClass(e.iconUp)}function P(e,i){e.each(function(e,s){t(s).before(i.clone(!0)).remove()})}function B(){var t=this;e.setTimeout(function(){if("true"===t.element._bgAria("busy")){var e=t.options.templates,i=t.element.children("thead").first(),s=t.element.children("tbody").first(),n=s.find("tr > td").first(),r=t.element.height()-i.height()-(n.height()+20),l=t.columns.where(d).length;t.selection&&(l+=1),s.html(e.loading.resolve(o.call(t,{columns:l}))),-1!==t.rowCount&&r>0&&s.find("tr > td").css("padding","20px 0 "+r+"px")}},250)}function j(){function t(i,s,n){function o(t){return"asc"===l.order?t:-1*t}var r=(n=n||0)+1,l=e[n];return i[l.id]>s[l.id]?o(1):i[l.id]<s[l.id]?o(-1):e.length>r?t(i,s,r):0}var e=[];if(!this.options.ajax){for(var i in this.sortDictionary)(this.options.multiSort||0===e.length)&&e.push({id:i,order:this.sortDictionary[i]});e.length>0&&this.rows.sort(t)}}var M=".rs.jquery.bootgrid",T=function(e,i){this.element=t(e),this.origin=this.element.clone(),this.options=t.extend(!0,{},T.defaults,this.element.data(),i);var s=this.options.rowCount=this.element.data().rowCount||i.rowCount||this.options.rowCount;this.columns=[],this.current=1,this.currentRows=[],this.identifier=null,this.selection=!1,this.converter=null,this.rowCount=t.isArray(s)?s[0]:s,this.rows=[],this.searchPhrase="",this.selectedRows=[],this.sortDictionary={},this.total=0,this.totalPages=0,this.cachedParams={lbl:this.options.labels,css:this.options.css,ctx:{}},this.header=null,this.footer=null,this.xqr=null};if(T.defaults={navigation:3,padding:2,columnSelection:!0,rowCount:[10,25,50,-1],selection:!1,multiSelect:!1,rowSelect:!1,keepSelection:!1,highlightRows:!1,sorting:!0,multiSort:!1,searchSettings:{delay:250,characters:1},ajax:!1,ajaxSettings:{method:"POST"},post:{},url:"",caseSensitive:!0,requestHandler:function(t){return t},responseHandler:function(t){return t},converters:{numeric:{from:function(t){return+t},to:function(t){return t+""}},string:{from:function(t){return t},to:function(t){return t}}},css:{actions:"actions btn-group",center:"text-center",columnHeaderAnchor:"column-header-anchor",columnHeaderText:"text",dropDownItem:"dropdown-item",dropDownItemButton:"dropdown-item-button",dropDownItemCheckbox:"dropdown-item-checkbox",dropDownMenu:"dropdown btn-group",dropDownMenuItems:"dropdown-menu pull-right",dropDownMenuText:"dropdown-text",footer:"bootgrid-footer container-fluid",header:"bootgrid-header container-fluid",icon:"icon glyphicon",iconColumns:"glyphicon-th-list",iconDown:"glyphicon-chevron-down",iconRefresh:"glyphicon-refresh",iconSearch:"glyphicon-search",iconUp:"glyphicon-chevron-up",infos:"infos",left:"text-left",pagination:"pagination",paginationButton:"button",responsiveTable:"table-responsive",right:"text-right",search:"search form-group",searchField:"search-field form-control",selectBox:"select-box",selectCell:"select-cell",selected:"active",sortable:"sortable",table:"bootgrid-table table"},formatters:{},labels:{all:"All",infos:"Showing {{ctx.start}} to {{ctx.end}} of {{ctx.total}} entries",loading:"Loading...",noResults:"No results found!",refresh:"Refresh",search:"Search"},statusMapping:{0:"success",1:"info",2:"warning",3:"danger"},templates:{actionButton:'<button class="btn btn-default" type="button" title="{{ctx.text}}">{{ctx.content}}</button>',actionDropDown:'<div class="{{css.dropDownMenu}}"><button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown"><span class="{{css.dropDownMenuText}}">{{ctx.content}}</span> <span class="caret"></span></button><ul class="{{css.dropDownMenuItems}}" role="menu"></ul></div>',actionDropDownItem:'<li><a data-action="{{ctx.action}}" class="{{css.dropDownItem}} {{css.dropDownItemButton}}">{{ctx.text}}</a></li>',actionDropDownCheckboxItem:'<li><label class="{{css.dropDownItem}}"><input name="{{ctx.name}}" type="checkbox" value="1" class="{{css.dropDownItemCheckbox}}" {{ctx.checked}} /> {{ctx.label}}</label></li>',actions:'<div class="{{css.actions}}"></div>',body:"<tbody></tbody>",cell:'<td class="{{ctx.css}}" style="{{ctx.style}}">{{ctx.content}}</td>',footer:'<div id="{{ctx.id}}" class="{{css.footer}}"><div class="row"><div class="col-sm-6"><p class="{{css.pagination}}"></p></div><div class="col-sm-6 infoBar"><p class="{{css.infos}}"></p></div></div></div>',header:'<div id="{{ctx.id}}" class="{{css.header}}"><div class="row"><div class="col-sm-12 actionBar"><p class="{{css.search}}"></p><p class="{{css.actions}}"></p></div></div></div>',headerCell:'<th data-column-id="{{ctx.column.id}}" class="{{ctx.css}}" style="{{ctx.style}}"><a href="javascript:void(0);" class="{{css.columnHeaderAnchor}} {{ctx.sortable}}"><span class="{{css.columnHeaderText}}">{{ctx.column.text}}</span>{{ctx.icon}}</a></th>',icon:'<span class="{{css.icon}} {{ctx.iconCss}}"></span>',infos:'<div class="{{css.infos}}">{{lbl.infos}}</div>',loading:'<tr><td colspan="{{ctx.columns}}" class="loading">{{lbl.loading}}</td></tr>',noResults:'<tr><td colspan="{{ctx.columns}}" class="no-results">{{lbl.noResults}}</td></tr>',pagination:'<ul class="{{css.pagination}}"></ul>',paginationItem:'<li class="{{ctx.css}}"><a data-page="{{ctx.page}}" class="{{css.paginationButton}}">{{ctx.text}}</a></li>',rawHeaderCell:'<th class="{{ctx.css}}">{{ctx.content}}</th>',row:"<tr{{ctx.attr}}>{{ctx.cells}}</tr>",search:'<div class="{{css.search}}"><div class="input-group"><span class="{{css.icon}} input-group-addon {{css.iconSearch}}"></span> <input type="text" class="{{css.searchField}}" placeholder="{{lbl.search}}" /></div></div>',select:'<input name="select" type="{{ctx.type}}" class="{{css.selectBox}}" value="{{ctx.value}}" {{ctx.checked}} />'}},T.prototype.append=function(t){if(this.options.ajax);else{for(var e=[],i=0;i<t.length;i++)s.call(this,t[i])&&e.push(t[i]);j.call(this),h.call(this,e),p.call(this),this.element.trigger("appended"+M,[e])}return this},T.prototype.clear=function(){if(this.options.ajax);else{var e=t.extend([],this.rows);this.rows=[],this.current=1,this.total=0,p.call(this),this.element.trigger("cleared"+M,[e])}return this},T.prototype.destroy=function(){return t(e).off(M),1&this.options.navigation&&this.header.remove(),2&this.options.navigation&&this.footer.remove(),this.element.before(this.origin).remove(),this},T.prototype.reload=function(){return this.current=1,p.call(this),this},T.prototype.remove=function(t){if(null!=this.identifier){if(this.options.ajax);else{t=t||this.selectedRows;for(var e,i=[],s=0;s<t.length;s++){e=t[s];for(var n=0;n<this.rows.length;n++)if(this.rows[n][this.identifier]===e){i.push(this.rows[n]),this.rows.splice(n,1);break}}this.current=1,p.call(this),this.element.trigger("removed"+M,[i])}}return this},T.prototype.search=function(t){if(t=t||"",this.searchPhrase!==t){var e=l(this.options.css.searchField);n.call(this,e).val(t)}return R.call(this,t),this},T.prototype.select=function(e){if(this.selection){e=e||this.currentRows.propValues(this.identifier);for(var i,s,n=[];e.length>0&&(this.options.multiSelect||1!==n.length);)if(i=e.pop(),-1===t.inArray(i,this.selectedRows))for(s=0;s<this.currentRows.length;s++)if(this.currentRows[s][this.identifier]===i){n.push(this.currentRows[s]),this.selectedRows.push(i);break}if(n.length>0){var o=l(this.options.css.selectBox),r=this.selectedRows.length>=this.currentRows.length;for(s=0;!this.options.keepSelection&&r&&s<this.currentRows.length;)r=-1!==t.inArray(this.currentRows[s++][this.identifier],this.selectedRows);for(this.element.find("thead "+o).prop("checked",r),this.options.multiSelect||this.element.find("tbody > tr "+o+":checked").trigger("click"+M),s=0;s<this.selectedRows.length;s++)this.element.find('tbody > tr[data-row-id="'+this.selectedRows[s]+'"]').addClass(this.options.css.selected)._bgAria("selected","true").find(o).prop("checked",!0);this.element.trigger("selected"+M,[n])}}return this},T.prototype.deselect=function(e){if(this.selection){e=e||this.currentRows.propValues(this.identifier);for(var i,s,n,o=[];e.length>0;)if(i=e.pop(),-1!==(n=t.inArray(i,this.selectedRows)))for(s=0;s<this.currentRows.length;s++)if(this.currentRows[s][this.identifier]===i){o.push(this.currentRows[s]),this.selectedRows.splice(n,1);break}if(o.length>0){var r=l(this.options.css.selectBox);for(this.element.find("thead "+r).prop("checked",!1),s=0;s<o.length;s++)this.element.find('tbody > tr[data-row-id="'+o[s][this.identifier]+'"]').removeClass(this.options.css.selected)._bgAria("selected","false").find(r).prop("checked",!1);this.element.trigger("deselected"+M,[o])}}return this},T.prototype.sort=function(e){var i=e?t.extend({},e):{};return i===this.sortDictionary?this:(this.sortDictionary=i,_.call(this),j.call(this),p.call(this),this)},T.prototype.getColumnSettings=function(){return t.merge([],this.columns)},T.prototype.getCurrentPage=function(){return this.current},T.prototype.getCurrentRows=function(){return t.merge([],this.currentRows)},T.prototype.getRowCount=function(){return this.rowCount},T.prototype.getSearchPhrase=function(){return this.searchPhrase},T.prototype.getSelectedRows=function(){return t.merge([],this.selectedRows)},T.prototype.getSortDictionary=function(){return t.extend({},this.sortDictionary)},T.prototype.getTotalPageCount=function(){return this.totalPages},T.prototype.getTotalRowCount=function(){return this.total},t.fn.extend({_bgAria:function(t,e){return e?this.attr("aria-"+t,e):this.attr("aria-"+t)},_bgBusyAria:function(t){return null==t||t?this._bgAria("busy","true"):this._bgAria("busy","false")},_bgRemoveAria:function(t){return this.removeAttr("aria-"+t)},_bgEnableAria:function(t){return null==t||t?this.removeClass("disabled")._bgAria("disabled","false"):this.addClass("disabled")._bgAria("disabled","true")},_bgEnableField:function(t){return null==t||t?this.removeAttr("disabled"):this.attr("disabled","disable")},_bgShowAria:function(t){return null==t||t?this.show()._bgAria("hidden","false"):this.hide()._bgAria("hidden","true")},_bgSelectAria:function(t){return null==t||t?this.addClass("active")._bgAria("selected","true"):this.removeClass("active")._bgAria("selected","false")},_bgId:function(t){return t?this.attr("id",t):this.attr("id")}}),!String.prototype.resolve){var E={checked:function(t){return"boolean"==typeof t?t?'checked="checked"':"":t}};String.prototype.resolve=function(e,i){var s=this;return t.each(e,function(e,n){if(null!=n&&"function"!=typeof n)if("object"==typeof n){var o=i?t.extend([],i):[];o.push(e),s=s.resolve(n,o)+""}else{E&&E[e]&&"function"==typeof E[e]&&(n=E[e](n)),e=i?i.join(".")+"."+e:e;var r=new RegExp("\\{\\{"+e+"\\}\\}","gm");s=s.replace(r,n.replace?n.replace(/\$/gi,"&#36;"):n)}}),s}}Array.prototype.first||(Array.prototype.first=function(t){for(var e=0;e<this.length;e++){var i=this[e];if(t(i))return i}return null}),Array.prototype.contains||(Array.prototype.contains=function(t){for(var e=0;e<this.length;e++)if(t(this[e]))return!0;return!1}),Array.prototype.page||(Array.prototype.page=function(t,e){var i=(t-1)*e,s=i+e;return this.length>i?this.length>s?this.slice(i,s):this.slice(i):[]}),Array.prototype.where||(Array.prototype.where=function(t){for(var e=[],i=0;i<this.length;i++){var s=this[i];t(s)&&e.push(s)}return e}),Array.prototype.propValues||(Array.prototype.propValues=function(t){for(var e=[],i=0;i<this.length;i++)e.push(this[i][t]);return e});var q=t.fn.bootgrid;t.fn.bootgrid=function(e){var i=Array.prototype.slice.call(arguments,1),s=null,n=this.each(function(n){var o=t(this),r=o.data(M),l="object"==typeof e&&e;if((r||"destroy"!==e)&&(r||(o.data(M,r=new T(this,l)),c.call(r)),"string"==typeof e))if(0===e.indexOf("get")&&0===n)s=r[e].apply(r,i);else if(0!==e.indexOf("get"))return r[e].apply(r,i)});return"string"==typeof e&&0===e.indexOf("get")?s:n},t.fn.bootgrid.Constructor=T,t.fn.bootgrid.noConflict=function(){return t.fn.bootgrid=q,this},t('[data-toggle="bootgrid"]').bootgrid()}(jQuery,window);