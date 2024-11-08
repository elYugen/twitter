import ReactDOM from "react-dom/client";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import Login from "./pages/Login";
import Register from "./pages/Register";
import Home from "./pages/Home";
import Layout from "./pages/Layout";
import NoPage from "./pages/404"
import Read from "./pages/Read"
import Profile from "./pages/Profile"
import Tendance from "./pages/Tendance";
import Explore from "./pages/Explore"
function App() {

  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Layout />}>
          <Route index element={<Login />} />
          <Route path="home" element={<Home/>}/>
          <Route path="login" element={<Login />} />
          <Route path="register" element={<Register />} />
          <Route path="tendance" element={<Tendance />} />
          <Route path="tendance/:hashtag" element={<Tendance />} />
          <Route path="read" element={<Read />} />
          <Route path="read/:id" element={<Read />} />
          <Route path="profile" element={<Profile />} />
          <Route path="profile/:id" element={<Profile />} />
          <Route path="explore" element={<Explore />} />
          <Route path="*" element={<NoPage />} />
        </Route>
      </Routes>
    </BrowserRouter>
  );
}

export default App
