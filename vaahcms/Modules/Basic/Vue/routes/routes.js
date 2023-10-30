let routes= [];

import dashboard from "./vue-routes-dashboard";
import articles from "./vue-routes-articles";
import blogs from "./vue-routes-blogs";

routes = routes.concat(dashboard);
routes = routes.concat(articles);
routes = routes.concat(blogs);

export default routes;
