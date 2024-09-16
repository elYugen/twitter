import Navbar from "../components/Navbar/Navbar"
import Sidebar from "../components/Sidebar/Sidebar"
import Feed from "../components/Feed/Feed"
import CreatePost from "../components/CreatePost/CreatePost"

function Home() {
    return (
        <>
        
        <div className="layout">
            <Navbar /> 
            <div className="container">
            <CreatePost/>
            <Feed/>
            <Feed/>
            <Feed/>
            </div>
            <Sidebar />
        </div>
        
        </>
    );
}

export default Home