let routes= [];
let routes_list= [];

import List from '../pages/blogs/List.vue'
import Form from '../pages/blogs/Form.vue'
import Item from '../pages/blogs/Item.vue'

routes_list = {

    path: '/blogs',
    name: 'blogs.index',
    component: List,
    props: true,
    children:[
        {
            path: 'form/:id?',
            name: 'blogs.form',
            component: Form,
            props: true,
        },
        {
            path: 'view/:id?',
            name: 'blogs.view',
            component: Item,
            props: true,
        }
    ]
};

routes.push(routes_list);

export default routes;

