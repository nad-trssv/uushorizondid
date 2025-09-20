import {createStore} from "vuex";
import auth from "./modules/auth";
import { services } from "./modules/services";
import { categories } from "./modules/categories";
import { settings } from "./modules/settings";
import { users } from "./modules/users";
import { appointments } from "./modules/appointments";
import { posts } from "./modules/posts";

export default new createStore({
    state: {},
    mutations: {},
    actions: {},
    modules: {
        auth,
        settings,
        services,
        categories,
        users,
        appointments,
        posts
    },
});