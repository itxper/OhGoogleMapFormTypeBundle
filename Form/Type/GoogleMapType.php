<?php

namespace Oh\GoogleMapFormTypeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GoogleMapType extends AbstractType
{

    private $api_key;

    public function __construct($api_key){
        $this->api_key = $api_key;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Default fields: latitude, longitude
        $builder
            ->add($options['lat_name'], $options['type'], array_merge($options['options'], $options['lat_options']))
            ->add($options['lng_name'], $options['type'], array_merge($options['options'], $options['lng_options']));

        // Optional Address field
        if (isset($options['addr_name']) && $options['addr_name']) {
            $builder->add($options['addr_name'], $options['addr_type'], array_merge($options['options'], $options['addr_options']));
        }

        // Optional Address field use button
        if (isset($options['addr_use_btn']) && $options['addr_use_btn']) {
            $builder->add(
                $options['addr_use_btn']['name'], ButtonType::class,
                array_merge(
                    $options['options'], isset($options['addr_use_btn']['options']) ? $options['addr_use_btn']['options'] : []
                )
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'api_key'          => $this->api_key,
            'type'             => HiddenType::class, // the types to render the lat and lng fields
            'addr_type'        => TextType::class, // the type to render the address field
            'attr'             => ['class' => 'form-group'],
            'search_enabled'   => true,
            'options'          => [], // the options for both the fields
            'lat_options'      => [], // the options for just the lat field
            'lng_options'      => [], // the options for just the lng field
            'addr_options'     => [], // the options for just the address field
            'addr_use_btn'     => [], // the options for the address use button
            'lat_name'         => 'latitude', // the name of the lat field
            'lng_name'         => 'longitude', // the name of the lng field
            'addr_name'        => 'address', // the name of the addr field
            'error_bubbling'   => false,
            'map_width'        => '100%', // the width of the map
            'map_height'       => '400px', // the height of the map
            'default_lat'      => 41.390205, // the starting position on the map
            'default_lng'      => 2.154007, // the starting position on the map
            'include_jquery'   => false, // jquery needs to be included above the field (ie not at the bottom of the page)
            'include_gmaps_js' => true     // is this the best place to include the google maps javascript?
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['api_key']          = $options['api_key'];
        $view->vars['search_enabled']   = $options['search_enabled'];
        $view->vars['lat_name']         = $options['lat_name'];
        $view->vars['lng_name']         = $options['lng_name'];
        $view->vars['addr_name']        = $options['addr_name'] ?: null;
        $view->vars['addr_use_btn']     = $options['addr_use_btn'] ?: [];
        $view->vars['map_width']        = $options['map_width'];
        $view->vars['map_height']       = $options['map_height'];
        $view->vars['default_lat']      = $options['default_lat'];
        $view->vars['default_lng']      = $options['default_lng'];
        $view->vars['include_jquery']   = $options['include_jquery'];
        $view->vars['include_gmaps_js'] = $options['include_gmaps_js'];
    }

    public function getParent(): string
    {
        return FormType::class;
    }

    public function getName(): string
    {
        return 'oh_google_maps';
    }

    public function getBlockPrefix(): string
    {
        return 'oh_google_maps';
    }
}
