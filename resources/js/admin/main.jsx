import ReactDOM from "react-dom/client";
import React, { Suspense } from "react";
import { Routes, Route, BrowserRouter, } from "react-router-dom";
ReactDOM.createRoot(document.getElementById("root")).render(
    <Suspense fallback={<div></div>}>
        <BrowserRouter>
            <Routes>
                <Route path="/" element={<div>234234</div>}>
                </Route>
            </Routes>
        </BrowserRouter>
    </Suspense>
);
