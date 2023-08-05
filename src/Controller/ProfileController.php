<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\CustomerAddress;
use App\Form\ChangePasswordFormType;
use App\Repository\CountryRepository;
use App\Repository\CustomerAddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private ValidatorInterface $validator)
    {
    }

    #[Route('/profile', name: 'profile')]
    public function view(CountryRepository $countryRepository): Response
    {
        $user = $this->getUser();
        $customer = $user->getCustomer();

        $shippingAddress = $customer->getShippingAddress() ?? new CustomerAddress();
        $billingAddress = $customer->getBillingAddress() ?? new CustomerAddress();
        $countries = array_map(fn($country) => $country->toArray(), $countryRepository->findAll());

        $updatePasswordForm = $this->createForm(ChangePasswordFormType::class, $user, [
            'action' => $this->generateUrl('profile_update_password')
        ]);

        return $this->render('profile/show.html.twig', [
            'customer' => $customer,
            'shippingAddress' => $shippingAddress,
            'billingAddress' => $billingAddress,
            'countries' => $countries,
            'updatePasswordForm' => $updatePasswordForm->createView(),
        ]);
    }

    #[Route('/profile/update', name: 'profile_update', methods: ["POST"])]
    public function update(Request $request, CustomerAddressRepository $customerAddressRepository): Response
    {
        if (!$this->isCsrfTokenValid('update-profile', $request->request->get('token'))) {
            die;
        }

        $customerData = $request->request->all('user');
        $billingAddressData = $request->request->all('billing');
        $shippingAddressData = $request->request->all('shipping');

        $customer = $this->getUser()->getCustomer()
            ->setFirstName($customerData['first_name'])
            ->setLastName($customerData['last_name'])
            ->setPhone($customerData['phone'])
        ;
        $billingAddress = $customerAddressRepository->createCustomerAddress($billingAddressData);
        $shippingAddress = $customerAddressRepository->createCustomerAddress($shippingAddressData);

        if ($this->isValidEntity($customer) && $this->isValidEntity($billingAddress) && $this->isValidEntity($shippingAddress)) {
            $customer->setBillingAddress($billingAddress)->setShippingAddress($shippingAddress);
            $this->entityManager->persist($customer);
            $this->entityManager->flush();
        } else {
            $this->addFlash('errors', 'There was an error with data you submited. Please try again!');
        }

        return $this->redirectToRoute('profile');
    }

    private function isValidEntity(mixed $entity): bool
    {
        return $this->validator->validate($entity)->count();
    }

    #[Route('/profile/update-password', name: 'profile_update_password', methods: ["POST"])]
    public function updatePassword(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(ChangePasswordFormType::class)->handleRequest($request);
        $user = $this->getUser();
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } elseif ($form->isSubmitted()) {
            foreach ($form->getErrors() as $error) {
                $this->addFlash('errors', $error->getMessage());
            }
        }

        return $this->redirectToRoute('profile');
    }
}
