import ReactDOM from "react-dom/client";
import React, { Suspense } from "react";
import { Routes, Route, BrowserRouter, Navigate, } from "react-router-dom";
import AppLayout from "./AppLayout";
import Dashboard from "./Dashboard";
import Users from "./Users";
import User from "./User";


ReactDOM.createRoot(document.getElementById("root")).render(
    <Suspense fallback={<div></div>}>
        <BrowserRouter>
            <Routes>
                <Route path="/admin" element={<AppLayout />} >
                    <Route exact path="dashboard" element={<Dashboard />} />
                    <Route exact path="users" element={<Users />} />
                    <Route exact path="users/:id" element={<User />} />
                    {/* <Route exact index element={<Dashboard />} /> */}
                </Route>
            </Routes>
        </BrowserRouter>
    </Suspense>
);
