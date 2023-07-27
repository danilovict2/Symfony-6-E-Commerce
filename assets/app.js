import { registerVueControllerComponents } from '@symfony/ux-vue';
import './bootstrap.js';
import './styles/app.scss';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse'

Alpine.plugin(collapse)
window.Alpine = Alpine;
Alpine.start();

registerVueControllerComponents(require.context('./vue/controllers', true, /\.vue$/));
