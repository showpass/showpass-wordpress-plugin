(window.webpackJsonp=window.webpackJsonp||[]).push([[1],{13:function(t,e,n){}}]),function(t){function e(e){for(var o,i,u=e[0],a=e[1],s=e[2],f=0,p=[];f<u.length;f++)i=u[f],Object.prototype.hasOwnProperty.call(r,i)&&r[i]&&p.push(r[i][0]),r[i]=0;for(o in a)Object.prototype.hasOwnProperty.call(a,o)&&(t[o]=a[o]);for(l&&l(e);p.length;)p.shift()();return c.push.apply(c,s||[]),n()}function n(){for(var t,e=0;e<c.length;e++){for(var n=c[e],o=!0,u=1;u<n.length;u++){var a=n[u];0!==r[a]&&(o=!1)}o&&(c.splice(e--,1),t=i(i.s=n[0]))}return t}var o={},r={0:0},c=[];function i(e){if(o[e])return o[e].exports;var n=o[e]={i:e,l:!1,exports:{}};return t[e].call(n.exports,n,n.exports,i),n.l=!0,n.exports}i.m=t,i.c=o,i.d=function(t,e,n){i.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:n})},i.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},i.t=function(t,e){if(1&e&&(t=i(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var n=Object.create(null);if(i.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var o in t)i.d(n,o,function(e){return t[e]}.bind(null,o));return n},i.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return i.d(e,"a",e),e},i.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},i.p="";var u=window.webpackJsonp=window.webpackJsonp||[],a=u.push.bind(u);u.push=e,u=u.slice();for(var s=0;s<u.length;s++)e(u[s]);var l=a;c.push([9,1]),n()}([function(t,e){!function(){t.exports=this.wp.element}()},function(t,e){!function(){t.exports=this.wp.components}()},function(t,e){function n(e){return t.exports=n=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)},n(e)}t.exports=n},function(t,e){t.exports=function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}},function(t,e){function n(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}t.exports=function(t,e,o){return e&&n(t.prototype,e),o&&n(t,o),t}},function(t,e,n){var o=n(10);t.exports=function(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),e&&o(t,e)}},function(t,e,n){var o=n(11),r=n(12);t.exports=function(t,e){return!e||"object"!==o(e)&&"function"!=typeof e?r(t):e}},function(t,e){!function(){t.exports=this.wp.apiFetch}()},function(t,e){!function(){t.exports=this.wp.blocks}()},function(t,e,n){"use strict";n.r(e);var o=n(3),r=n.n(o),c=n(4),i=n.n(c),u=n(5),a=n.n(u),s=n(6),l=n.n(s),f=n(2),p=n.n(f),b=n(0),y=n(7),h=n.n(y),d=n(8),g=n(1);n(13),n(14);function v(t){var e=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(t){return!1}}();return function(){var n,o=p()(t);if(e){var r=p()(this).constructor;n=Reflect.construct(o,arguments,r)}else n=o.apply(this,arguments);return l()(this,n)}}var w=function(t){a()(n,t);var e=v(n);function n(t){var o;return r()(this,n),(o=e.call(this,t)).state={loading:!1,errorMessage:""},o}return i()(n,[{key:"render",value:function(){var t=this,e=this.props,n=e.attributes,o=n.ticketLink,r=n.buttonLabel,c=n.dataError,i=e.setAttributes,u=function(t){if(t)return h()({path:"showpass/v1/process-url/?url="+encodeURI(t),method:"GET"})};return Object(b.createElement)("div",{class:"wp-showpass-block-container"},Object(b.createElement)("span",{class:"dashicons dashicons-tickets-alt"}),Object(b.createElement)("h4",null,"Buy Now Button"),Object(b.createElement)(g.TextControl,{label:"Button Label",value:r,onChange:function(t){i({buttonLabel:t})},key:"ticketLink",default:"Buy Now"}),Object(b.createElement)(g.TextControl,{label:"Enter in the full URL",value:o,onChange:function(t){i({ticketLink:t})},key:"ticketLink",help:"Example: https://showpass.com/event-slug/"}),Object(b.createElement)("div",{class:"control-container"},Object(b.createElement)(g.Button,{isSecondary:!0,isBusy:this.state.loading,onClick:function(){i({dataError:null}),t.setState({loading:!0,errorMessage:""}),u(o).then((function(e){console.log(e),t.setState({loading:!1}),e&&(i({slug:e}),i({dataError:!1}))})).catch((function(e){t.setState({loading:!1,errorMessage:e.data}),console.log(e),i({dataError:!0})}))}},"Add Button!"),this.state.loading&&Object(b.createElement)(g.Spinner,null),c&&Object(b.createElement)(g.Dashicon,{className:"validate",icon:"no"}),!1===c&&Object(b.createElement)(g.Dashicon,{className:"validate",icon:"yes"}),this.state.errorMessage&&Object(b.createElement)("p",{class:"error-message"},this.state.errorMessage)))}}]),n}(b.Component);Object(d.registerBlockType)("create-block/showpass-button-block",{title:"Buy Now Button",category:"showpass-blocks",icon:"tickets-alt",supports:{},attributes:{ticketLink:{type:"string"},buttonLabel:{type:"string",default:"Buy Now"},slug:{type:"string"},dataError:{type:"boolean",default:null}},edit:w,save:function(t){var e=t.attributes;return!e.dataError&&e.slug&&'[showpass_widget slug="'+e.slug+'" label="'+e.buttonLabel+'"]'}})},function(t,e){function n(e,o){return t.exports=n=Object.setPrototypeOf||function(t,e){return t.__proto__=e,t},n(e,o)}t.exports=n},function(t,e){function n(e){return"function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?t.exports=n=function(t){return typeof t}:t.exports=n=function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},n(e)}t.exports=n},function(t,e){t.exports=function(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}},,function(t,e,n){}]);