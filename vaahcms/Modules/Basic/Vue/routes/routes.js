let routes= [];

import dashboard from "./vue-routes-dashboard";
import blogs from "./vue-routes-blogs";

routes = routes.concat(dashboard);
routes = routes.concat(blogs);

export default routes;
