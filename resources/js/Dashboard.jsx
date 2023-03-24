import React from 'react';
// PAGE
import Admin from './Framework/Pages/Admin';
// RESOURCES
import Resource from './Framework/Resource';
// ROUTES
import { logoutRoutes } from './Routes/web';
// PAGES
import UserList from './Pages/Users/UserList';
import TagList from './Pages/Tags/TagList';
import TagForm from './Pages/Tags/TagForm';
import AuthorList from './Pages/Authors/AuthorList';
import AuthorForm from './Pages/Authors/AuthorForm';
import BrandList from './Pages/Brands/BrandList';
import BrandForm from './Pages/Brands/BrandForm';
import BrandChangeRequestList from './Pages/BrandChangeRequests/BrandChangeRequestList';
import BrandChangeRequestForm from './Pages/BrandChangeRequests/BrandChangeRequestForm';
import ArticleList from './Pages/Articles/ArticleList';
import ArticleForm from './Pages/Articles/ArticleForm';
import ProductList from './Pages/Products/ProductList';
import ProductForm from './Pages/Products/ProductForm';
import IngredientList from './Pages/Ingredients/IngredientList';
import IngredientForm from './Pages/Ingredients/IngredientForm';
import IngredientCategoryList from './Pages/Categories/Lists/IngredientCategoryList';
import ProductCategoryList from './Pages/Categories/Lists/ProductCategoryList';
import CategoryForm from './Pages/Categories/CategoryForm';
import SkinTypeCategoryList from './Pages/Categories/Lists/SkinTypeCategoryList';
import SkinConcernCategoryList from './Pages/Categories/Lists/SkinConcernCategoryList';
import ArticleCategoryList from './Pages/Categories/Lists/ArticleCategoryList';
import HairProblemCategoryList from './Pages/Categories/Lists/HairProblemCategoryList';

const Dashboard = () => {
    return (
        <Admin>
            <Resource name="users" list={UserList} />
            <Resource name="authors" list={AuthorList} form={AuthorForm} />
            <Resource name="articles" list={ArticleList} form={ArticleForm} />
            <Resource name="brands" list={BrandList} form={BrandForm} />
            <Resource
                name="brand-change-requests"
                list={BrandChangeRequestList}
                approve={BrandChangeRequestForm}
            />
            <Resource name="tags" list={TagList} form={TagForm} />
            <Resource name="products" list={ProductList} form={ProductForm} />
            <Resource
                name="ingredients"
                list={IngredientList}
                form={IngredientForm}
            />
            <Resource name="categories" groupParent group="categories">
                <Resource
                    name="categories-article"
                    list={ArticleCategoryList}
                    form={CategoryForm}
                    group="categories"
                />
                <Resource
                    name="categories-product"
                    list={ProductCategoryList}
                    form={CategoryForm}
                    group="categories"
                />
                <Resource
                    name="categories-skintype"
                    list={SkinTypeCategoryList}
                    form={CategoryForm}
                    group="categories"
                />
                <Resource
                    name="categories-skinconcern"
                    list={SkinConcernCategoryList}
                    form={CategoryForm}
                    group="categories"
                />
                <Resource
                    name="categories-hairproblem"
                    list={HairProblemCategoryList}
                    form={CategoryForm}
                    group="categories"
                />
                <Resource
                    name="categories-ingredient"
                    list={IngredientCategoryList}
                    form={CategoryForm}
                    group="categories"
                />
            </Resource>
            <Resource
                name="logout"
                routes={logoutRoutes}
                requiresPermission={false}
            />
        </Admin>
    );
};

export default Dashboard;
