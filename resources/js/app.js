import './bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';

import { createApp } from 'vue';
import App from './components/App.vue';
import ExerciseList from './components/exercises/ExerciseList.vue';
import ExerciseSearch from './components/exercises/ExerciseSearch.vue';
import FoodList from './components/foods/FoodList.vue';
import FoodSearch from './components/foods/FoodSearch.vue';

const app = createApp(App);

app.component('exercise-list', ExerciseList);
app.component('exercise-search', ExerciseSearch);
app.component('food-list', FoodList);
app.component('food-search', FoodSearch);

app.mount('#app');