import React from 'react';

// Components
import Form from '../../Framework/Components/Form/Form';
import TextInput from '../../Framework/Components/Form/TextInput';
import Image from '../../Framework/Components/Image/Image';
import Toggle from '../../Framework/Components/Form/Toggle';
import BlockStyledEditor from '../../Framework/Components/Form/BlockStyledEditor';
import TagInput from '../../Framework/Components/Form/TagInput';
import CategoryInput from '../../Framework/Components/Form/CategoryInput';
import DateTime from '../../Framework/Components/Form/DateTime';
import BrandInput from '../../Framework/Components/Form/BrandInput';
import IngredientInput from '../../Framework/Components/Form/IngredientInput';
import Grid, { GridLeft, GridRight } from '../../Framework/Layouts/Grid';

const ProductForm = (props) => {
    return (
        <Form {...props}>
            <Grid>
                <GridLeft>
                    <TextInput name="name" />
                    <TextInput name="canonical_name" />
                    <TextInput name="price" />
                    <TextInput name="size" />
                    <TextInput name="where_to_find" />
                    <BlockStyledEditor name="description" fullFeatureSet />
                </GridLeft>
                <GridRight>
                    <Toggle name="is_active" />
                    <Toggle name="is_sponsored" />
                    <Toggle name="is_18_plus" />
                    <Image name="image" isCropping={false} />
                    <BrandInput name="brand" />
                    <CategoryInput
                        name="category"
                        type="product"
                        isMultiple={false}
                    />
                    <CategoryInput name="skin_types" type="skintype" />
                    <CategoryInput name="skin_concerns" type="skinconcern" />
                    <CategoryInput name="hair_problems" type="hairproblem" />
                    <IngredientInput name="ingredients" />
                    <TagInput name="tags" />
                    <DateTime name="published_at" readOnly />
                </GridRight>
            </Grid>
        </Form>
    );
};

export default ProductForm;
