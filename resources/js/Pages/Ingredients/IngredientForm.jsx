import React, { useState, useEffect } from 'react';
import PropTypes from 'prop-types';

// Components
import Form from '../../Framework/Components/Form/Form';
import TextInput from '../../Framework/Components/Form/TextInput';
import Panel from '../../Framework/Components/Panel';
import Flex from '../../Framework/Layouts/Flex';
import Number from '../../Framework/Components/Form/Number';
import BlockStyledEditor from '../../Framework/Components/Form/BlockStyledEditor';
import StaticDropDown from '../../Framework/Components/Form/StaticDropDown';
import StaticAutoComplete from '../../Framework/Components/Form/StaticAutoComplete';

const IngredientForm = (props) => {
    const [categories, setCategories] = useState();

    useEffect(() => {
        const { service } = props;

        service.get('ingredients/get-categories').then((result) => {
            setCategories(
                result.map((item) => ({
                    id: item.id ?? item,
                    name: item.name,
                })),
            );
        });
    }, []);

    return (
        <Form {...props}>
            <Flex>
                <Panel>
                    <TextInput name="name" />
                    <BlockStyledEditor name="description" />
                </Panel>
                <Panel>
                    <StaticAutoComplete
                        name="categories"
                        items={categories}
                        isMultiple
                    />
                    <StaticDropDown
                        path="ingredients/get-ewg-data-types"
                        name="ewg_data"
                    />
                    <Number name="ewg_score" min="0" max="10" />
                    <Number name="ewg_score_max" min="0" max="10" />
                    <Number name="comedogen_index" min="0" max="5" />
                </Panel>
            </Flex>
        </Form>
    );
};

export default IngredientForm;

IngredientForm.propTypes = {
    /**
     * Type of service
     */
    service: PropTypes.object.isRequired,
};
