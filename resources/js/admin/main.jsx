import ReactDOM from "react-dom/client";
import React, { Suspense } from "react";
import { Routes, Route, BrowserRouter, Navigate, } from "react-router-dom";
import AppLayout from "./AppLayout";
import Dashboard from "./Dashboard";
import User from "./User";


ReactDOM.createRoot(document.getElementById("root")).render(
    <Suspense fallback={<div></div>}>
        <BrowserRouter>
            <Routes>
                <Route path="/admin" element={<AppLayout />} >
                    <Route exact path="dashboard" element={<Dashboard />} />
                    <Route exact path="user" element={<User />} />
                    <Route exact path="user/:id" element={<User />} />
                    {/* <Route exact index element={<Dashboard />} /> */}
                </Route>
            </Routes>
        </BrowserRouter>
    </Suspense>
);
