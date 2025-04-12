import { createBrowserRouter } from "react-router-dom";
import App from "./App";
import Dashboards from "./views/Dashboards";
import Login from "./views/Login";
import Signup from "./views/Signup";
import Surveys from "./views/Surveys";

const router = createBrowserRouter([
    {
        path: "/",
        element: <Dashboards />,
    },
    {
        path: "/login",
        element: <Login />,
    },
    {
        path: "/surveys",
        element: <Surveys />,
    },
    {
        path: "/sign-up",
        element: <Signup />,
    },
]);

export default router;
