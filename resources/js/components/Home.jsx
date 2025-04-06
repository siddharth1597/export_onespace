import axios from 'axios';
import React, { useEffect, useState } from 'react';

import { Link } from 'react-router-dom';

const Home = () => {

    const [id, setId] = useState("");

    // useEffect(() => {
    //     axios.get('/get-user').then((response) => {
    //         console.log(response.data);
    //         setId(response.data.id)
    //     })
    // }, [])

    return (
        <div className="container px-0 pt-2 pb-0 text-center mt-5 homeHeight">
            <div className="row justify-content-center mt-4">
                <div className="col-md-4">
                    <Link to="/" className="text-decoration-none">
                        <div className="card shadow cursor-pointer px-5 px-sm-0 py-sm-4 btn-grad-2">
                            <div className="card-body m-auto">
                                <i className="fa-solid fa-database text-white h1 mb-3"></i>
                                <h2 className="text-white">Item Masters</h2>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>
            <hr />
            {/* <a href={`http://127.0.0.1:8001/autologin?id=${id}&api_token=token`}>Pass uid</a> */}
        </div>
    );
}

export default Home;
