import './bootstrap';

import Alpine from 'alpinejs';
import 'toastr/build/toastr.min.css';
import toastr from 'toastr';

import './handlingBrand.js';
import './location.js';

window.toastr = toastr;
window.Alpine = Alpine;

Alpine.start();
